import{U as s}from"./UrlBuilder-DtWhPO4P.js";jQuery(document).ready(function(){var f="a.ajaxbox",c,i=jQuery("body"),a={},g=function(e){var n={showCloseBtn:!1,modal:e.modal,callbacks:{open:function(){this.contentContainer.trigger("ajaxboxaftershow",[this,e])},beforeClose:function(){this.contentContainer.trigger("ajaxboxbeforeclose")}}};e.type==="image"?(n.items={src:e.url},n.type="image"):n.items={src:e.content,type:"inline"},jQuery.magnificPopup.open(n)},u=function(){jQuery.magnificPopup.close()};i.on("ajaxboxaftershow",function(e,n,o){if(n.contentContainer.addClass("ajaxbox-inner"),n.wrap.draggable!==void 0&&n.wrap.draggable({handle:n.contentContainer.find("section header").first()}),!o.modal){var t=n.contentContainer.first();if(o.type!=="image"&&(t=n.contentContainer.find("section header").first()),t.length>0){var r=jQuery("<i />").addClass("mt-icon").html("close"),d=jQuery("<button />").attr("type","button").addClass("button secondary close only-icon").append(r);d.on("click",function(){u()}),t.append(d)}}var l=n.contentContainer.find('section footer .button[data-type="cancel"]').first();l.length>0&&(l.bind("click",function(){return i.trigger("ajaxboxclose"),!1}),l.focus()),n.contentContainer.trigger("contentloaded"),n.contentContainer.trigger("ajaxboxdone",o)}),i.on("ajaxboxinit",function(e){var n=jQuery(e.target),o=n.is(f)?n:n.find(f);o.on("click",function(){var t=jQuery(this),r={url:new s(t.attr("href")).add({ajax:1}).getUrl(),modal:t.is("[data-modal]"),trigger:t,cache:t.is("[data-cache]")};return t.attr("rel")==="image"&&(r.type="image"),t.trigger("ajaxboxopen",r),!1})}),i.on("ajaxboxopen",function(e,n){if("cache"in n&&n.cache===!0){var o=n.url in a;o&&(n.content=a[n.url])}"content"in n?g(n):"url"in n&&("trigger"in n&&n.trigger.trigger("loadingstart"),c&&c.abort(),c=jQuery.ajax({url:n.url,type:"get",success:function(t){n.content=t,g(n),n.cache&&(a[n.url]=t)}}))}),i.on("ajaxboxdone",function(e,n){n&&"trigger"in n&&n.trigger.trigger("loadingend"),jQuery(e.target).find(".dialog").trigger("contentdone")}),i.on("ajaxboxclose",function(){u()}),i.on("contentloaded",function(e){jQuery(e.target).trigger("ajaxboxinit")})});
