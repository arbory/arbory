class l{constructor(e){e===void 0&&(e={});var n=!0;(e===!1||e.baseUrl!==void 0)&&(n=!1),typeof e=="string"&&(e={baseUrl:e}),this.path="",this.query={};var t=e.baseUrl||location.href;t=t.split("#").shift();var i=t.split("?");if(this.path=i.shift(),n&&i.length>0){for(var u=i.shift().split("&"),f=0;f<u.length;f++)if(u[f].length>0){var h,o=u[f].split("="),r=o.shift();o.length>0?h=decodeURIComponent(o.shift()):h="",decodeURIComponent(r).substr(decodeURIComponent(r).length-2,2)==="[]"&&(r=decodeURIComponent(r)),r.substr(r.length-2,2)==="[]"?(r=r.substr(0,r.length-2),(this.query[r]===void 0||!(this.query[r]instanceof Array))&&(this.query[r]=[]),this.query[r].push(h)):this.query[r]=h}}if(e.keep!==void 0&&e.keep instanceof Array){for(var y={},s=0;s<e.keep.length;s++)this.query[e.keep[s]]!==void 0&&(y[e.keep[s]]=this.query[e.keep[s]]);this.query=y}}add(e,n){if(e instanceof Array){for(var t=0;t<e.length;t++)if(e[t].name!==void 0&&e[t].value!==void 0){var i=e[t].name;i.substr(i.length-2,2)==="[]"?(i=i.substr(0,i.length-2),(this.query[i]===void 0||!(this.query[i]instanceof Array))&&(this.query[i]=[]),this.query[i].push(e[t].value)):this.query[e[t].name]=e[t].value}}else if(e instanceof Object)for(var u in e)e.hasOwnProperty(u)&&(this.query[u]=e[u]);else if(typeof e=="string")if(n===void 0){var f=new l("?"+e);for(var h in f.query)f.query.hasOwnProperty(h)&&(this.query[h]=f.query[h])}else this.query[e]=n;return this}removeAll(e){for(var n in this.query)(e===void 0||jQuery.inArray(n,e)===-1)&&this.remove(n);return this}remove(e){return delete this.query[e],this}get(e){return this.query[e]!==void 0?this.query[e]:null}getUrl(){var e="",n=!0;for(var t in this.query)this.query.hasOwnProperty(t)&&(n?n=!1:e+="&",this.query[t]instanceof Array?e+=t+"[]="+this.query[t].map(encodeURIComponent).join("&"+t+"[]="):e+=t+"="+encodeURIComponent(this.query[t]));return this.path+"?"+e}}export{l as U};