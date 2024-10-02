<?php

namespace ClarkWinkelmann\Scout\Search;

use ClarkWinkelmann\Scout\ScoutStatic;
use Flarum\Discussion\Discussion;
use Flarum\Post\Post;
use Flarum\Search\GambitInterface;
use Flarum\Search\SearchState;
use Illuminate\Database\Query\Expression;

class DiscussionGambit implements GambitInterface
{
    protected static $MAX_PRIORITY_NUMBER = 0x7fffffff;
    public function apply(SearchState $search, $bit)
    {
        $discussionBuilder = ScoutStatic::makeBuilder(Discussion::class, $bit);

        $discussionIds = $discussionBuilder->keys()->all();
        $discussionIdsCount = count($discussionIds);

        $postBuilder = ScoutStatic::makeBuilder(Post::class, $bit);

        $postIds = $postBuilder->keys()->all();
        $postIdsCount = count($postIds);

        // We could replace the "where field" with "where false" everywhere when there are no IDs, but it's easier to
        // keep a FIELD() statement and just hard-code some values to prevent SQL errors
        // we know nothing will be returned anyway, so it doesn't really matter what impact it has on the query
        $postIdsSql = $postIdsCount > 0 ? str_repeat(', ?', count($postIds)) : ', 0';
        // Do the same for the discussion IDs as we'll need it in later query
        $discussionIdsSql = $discussionIdsCount > 0 ? str_repeat(', ?', count($discussionIds)) : ', 0';
        $discussionIdsArrSql = $discussionIdsCount > 0 ? implode(',', $discussionIds) : '0';

        $query = $search->getQuery();
        $grammar = $query->getGrammar();

        $allMatchingPostsQuery = Post::whereVisibleTo($search->getActor())
            ->select('posts.discussion_id')
            ->selectRaw('FIELD(id' . $postIdsSql . ') as priority', $postIds)
            ->where('posts.type', 'comment')
            ->whereIn('id', $postIds);

        // Using wrap() instead of wrapTable() in join subquery to skip table prefixes
        // Using raw() in the join table name to use the same prefixless name
        $bestMatchingPostQuery = Post::query()
            ->select('posts.discussion_id')
            ->selectRaw('min(matching_posts.priority) as min_priority')
            ->join(
                new Expression('(' . $allMatchingPostsQuery->toSql() . ') ' . $grammar->wrap('matching_posts')),
                $query->raw('matching_posts.discussion_id'),
                '=',
                'posts.discussion_id'
            )
            ->groupBy('posts.discussion_id')
            ->addBinding($allMatchingPostsQuery->getBindings(), 'join');

        // Code based on Flarum\Discussion\Search\Gambit\FulltextGambit
        $subquery = Post::whereVisibleTo($search->getActor())
            ->select('posts.discussion_id')
            ->selectRaw('id as most_relevant_post_id')
            ->selectRaw('best_matching_posts.min_priority as min_priority')
            ->join(
                new Expression('(' . $bestMatchingPostQuery->toSql() . ') ' . $grammar->wrap('best_matching_posts')),
                $query->raw('best_matching_posts.discussion_id'),
                '=',
                'posts.discussion_id'
            )
            ->whereIn('id', $postIds)
            ->whereRaw('FIELD(id' . $postIdsSql . ') = best_matching_posts.min_priority', $postIds)
            ->addBinding($bestMatchingPostQuery->getBindings(), 'join');

        $query
            ->where(function (\Illuminate\Database\Query\Builder $query) use ($discussionIds) {
                $query
                    ->whereNotNull('most_relevant_post_id')
                    ->orWhereIn('id', $discussionIds);
            })
            // We calculate the priority of the results by to values:
            // If the result is in discussion list, we use the priority of the discussion
            // If the result is not in discussion list, we use the priority of the post
            // Then, take the smaller one as finally priority value (I call it 'mixed priority').
            ->selectRaw('LEAST(CASE WHEN id IN (' . $discussionIdsArrSql . ') THEN FIELD(id' . $discussionIdsSql . ') ELSE '.self::$MAX_PRIORITY_NUMBER.' END,COALESCE(posts_ft.min_priority,'.self::$MAX_PRIORITY_NUMBER.')) as min_priority_mixed', $discussionIds)
            ->selectRaw('COALESCE(posts_ft.most_relevant_post_id, ' . $grammar->wrapTable('discussions') . '.first_post_id) as most_relevant_post_id')
            ->leftJoin(
                new Expression('(' . $subquery->toSql() . ') ' . $grammar->wrap('posts_ft')),
                $query->raw('posts_ft.discussion_id'),
                '=',
                'discussions.id'
            )
            ->groupBy('discussions.id')
            ->addBinding($subquery->getBindings(), 'join');

        $search->setDefaultSort(function ($query) use ($postIdsSql, $postIds) {
            $query->orderBy('min_priority_mixed');
        });
    }
}
