(()=>{var t={n:n=>{var e=n&&n.__esModule?()=>n.default:()=>n;return t.d(e,{a:e}),e},d:(n,e)=>{for(var a in e)t.o(e,a)&&!t.o(n,a)&&Object.defineProperty(n,a,{enumerable:!0,get:e[a]})},o:(t,n)=>Object.prototype.hasOwnProperty.call(t,n),r:t=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})}},n={};(()=>{"use strict";t.r(n);const e=flarum.core.compat["admin/app"];var a=t.n(e);a().initializers.add("clarkwinkelmann-scout",(function(){a().extensionData.for("clarkwinkelmann-scout").registerSetting({type:"select",setting:"clarkwinkelmann-scout.driver",options:{null:a().translator.trans("clarkwinkelmann-scout.admin.setting.driverOption.null"),algolia:a().translator.trans("clarkwinkelmann-scout.admin.setting.driverOption.algolia"),meilisearch:a().translator.trans("clarkwinkelmann-scout.admin.setting.driverOption.meilisearch"),tntsearch:a().translator.trans("clarkwinkelmann-scout.admin.setting.driverOption.tntsearch")},default:"null",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.driver")}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.prefix",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.prefix")}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.algoliaId",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.algoliaId")}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.algoliaSecret",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.algoliaSecret")}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.algoliaConnectTimeout",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.algoliaConnectTimeout")}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.algoliaReadTimeout",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.algoliaReadTimeout")}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.algoliaWriteTimeout",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.algoliaWriteTimeout")}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.meilisearchHost",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.meilisearchHost"),placeholder:"127.0.0.1:7700"}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.meilisearchKey",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.meilisearchKey")}).registerSetting({type:"number",setting:"clarkwinkelmann-scout.tntsearchMaxDocs",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.tntsearchMaxDocs"),placeholder:"500"}).registerSetting({type:"switch",setting:"clarkwinkelmann-scout.tntsearchFuzziness",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.tntsearchFuzziness")}).registerSetting({type:"number",setting:"clarkwinkelmann-scout.tntsearchFuzzyDistance",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.tntsearchFuzzyDistance"),placeholder:"2"}).registerSetting({type:"number",setting:"clarkwinkelmann-scout.tntsearchFuzzyPrefixLength",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.tntsearchFuzzyPrefixLength"),placeholder:"50"}).registerSetting({type:"text",setting:"clarkwinkelmann-scout.tntsearchFuzzyMaxExpansions",label:a().translator.trans("clarkwinkelmann-scout.admin.setting.tntsearchFuzzyMaxExpansions"),placeholder:"2"})}))})(),module.exports=n})();
//# sourceMappingURL=admin.js.map