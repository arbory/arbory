import{U as a}from"./UrlBuilder-DtWhPO4P.js";jQuery(function(){var e=jQuery("body");e.on("contentloaded",function(o){jQuery(o.target).find('.pagination select[name="page"]').on("change",function(){var n=jQuery(this).val();n&&(window.location.href=new a(window.location.href).add({page:n}).getUrl())})})});
