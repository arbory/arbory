import{C as i}from"./js.cookie-4MBKI6gU.js";import"./_commonjsHelpers-Cpj98o6Y.js";const l="menu",a="li[data-name]";class r{constructor(e){this.element=e}getItemElements(){return this.element.find(a)}getItems(){let e=[];return jQuery.each(this.getItemElements(),(t,o)=>{e.push(new c(this,jQuery(o)))}),e}collapseItems(){jQuery.each(this.getItems(),(e,t)=>{t.collapseItems()})}isCompact(){return this.element.closest("body").hasClass("side-compact")}}class c{constructor(e,t){this.menu=e,this.element=t,this.name=t.data("name")}getStorage(){return s.get(this.name)}getChildBlockElement(){return this.element.find(".block:first")}getChildElements(){return this.getChildBlockElement().children(a)}hasChildren(){return this.getChildElements().length}getIconElement(){return this.element.children(".trigger").find(".collapser i")}toggleItems(){this.hasChildren()&&(this.menu.isCompact()&&this.menu.collapseItems(),this.isCollapsed()?this.expandItems():this.collapseItems())}isCollapsed(){return this.menu.isCompact()?!this.element.hasClass("open"):this.element.hasClass("collapsed")}collapseItems(){this.menu.isCompact()?this.element.removeClass("open"):this.element.addClass("collapsed")}expandItems(){this.menu.isCompact()?this.element.addClass("open"):this.element.removeClass("collapsed")}updateIcon(){this.getIconElement().toggleClass("collapsed",this.menu.isCompact())}}class s{static getStored(){const e=i.getJSON(l);return typeof e>"u"?(s.save({}),{}):e}static get(e){let t=this.getStored();return typeof t[e]>"u"&&(t[e]=null,s.save(t)),new h(e,t[e])}static saveItem(e){let t=this.getStored();t[e.id]=e.getContents(),s.save(t)}static save(e){i.set(l,JSON.stringify(e))}}class h{constructor(e,t={}){this.id=e,this.contents=t}getContents(){return this.contents}isCollapsed(){return this.contents}setCollapsed(e){this.contents=e,this.save()}save(){s.saveItem(this)}}jQuery(document).ready(function(){let n=new r(jQuery("aside nav > ul"));jQuery.each(n.getItems(),(e,t)=>{t.updateIcon(),t.element.find(".trigger:first").on("click",()=>{t.toggleItems(),t.updateIcon(),!n.isCompact()&&t.hasChildren()&&t.getStorage().setCollapsed(t.isCollapsed())})})});
