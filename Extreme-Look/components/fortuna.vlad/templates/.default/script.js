(function() {
    'use strict';
    var popupContainerBX = BX('extremefortuna-container');
    const datetoday = new Date;

    !function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof module&&module.exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){a.extend(a.fn,{validate:function(b){if(!this.length)return void(b&&b.debug&&window.console&&console.warn("Nothing selected, can't validate, returning nothing."));var c=a.data(this[0],"validator");return c?c:(this.attr("novalidate","novalidate"),c=new a.validator(b,this[0]),a.data(this[0],"validator",c),c.settings.onsubmit&&(this.on("click.validate",":submit",function(b){c.settings.submitHandler&&(c.submitButton=b.target),a(this).hasClass("cancel")&&(c.cancelSubmit=!0),void 0!==a(this).attr("formnovalidate")&&(c.cancelSubmit=!0)}),this.on("submit.validate",function(b){function d(){var d,e;return!c.settings.submitHandler||(c.submitButton&&(d=a("<input type='hidden'/>").attr("name",c.submitButton.name).val(a(c.submitButton).val()).appendTo(c.currentForm)),e=c.settings.submitHandler.call(c,c.currentForm,b),c.submitButton&&d.remove(),void 0!==e&&e)}return c.settings.debug&&b.preventDefault(),c.cancelSubmit?(c.cancelSubmit=!1,d()):c.form()?c.pendingRequest?(c.formSubmitted=!0,!1):d():(c.focusInvalid(),!1)})),c)},valid:function(){var b,c,d;return a(this[0]).is("form")?b=this.validate().form():(d=[],b=!0,c=a(this[0].form).validate(),this.each(function(){b=c.element(this)&&b,b||(d=d.concat(c.errorList))}),c.errorList=d),b},rules:function(b,c){var d,e,f,g,h,i,j=this[0];if(null!=j&&null!=j.form){if(b)switch(d=a.data(j.form,"validator").settings,e=d.rules,f=a.validator.staticRules(j),b){case"add":a.extend(f,a.validator.normalizeRule(c)),delete f.messages,e[j.name]=f,c.messages&&(d.messages[j.name]=a.extend(d.messages[j.name],c.messages));break;case"remove":return c?(i={},a.each(c.split(/\s/),function(b,c){i[c]=f[c],delete f[c],"required"===c&&a(j).removeAttr("aria-required")}),i):(delete e[j.name],f)}return g=a.validator.normalizeRules(a.extend({},a.validator.classRules(j),a.validator.attributeRules(j),a.validator.dataRules(j),a.validator.staticRules(j)),j),g.required&&(h=g.required,delete g.required,g=a.extend({required:h},g),a(j).attr("aria-required","true")),g.remote&&(h=g.remote,delete g.remote,g=a.extend(g,{remote:h})),g}}}),a.extend(a.expr[":"],{blank:function(b){return!a.trim(""+a(b).val())},filled:function(b){var c=a(b).val();return null!==c&&!!a.trim(""+c)},unchecked:function(b){return!a(b).prop("checked")}}),a.validator=function(b,c){this.settings=a.extend(!0,{},a.validator.defaults,b),this.currentForm=c,this.init()},a.validator.format=function(b,c){return 1===arguments.length?function(){var c=a.makeArray(arguments);return c.unshift(b),a.validator.format.apply(this,c)}:void 0===c?b:(arguments.length>2&&c.constructor!==Array&&(c=a.makeArray(arguments).slice(1)),c.constructor!==Array&&(c=[c]),a.each(c,function(a,c){b=b.replace(new RegExp("\\{"+a+"\\}","g"),function(){return c})}),b)},a.extend(a.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",pendingClass:"pending",validClass:"valid",errorElement:"label",focusCleanup:!1,focusInvalid:!0,errorContainer:a([]),errorLabelContainer:a([]),onsubmit:!0,ignore:":hidden",ignoreTitle:!1,onfocusin:function(a){this.lastActive=a,this.settings.focusCleanup&&(this.settings.unhighlight&&this.settings.unhighlight.call(this,a,this.settings.errorClass,this.settings.validClass),this.hideThese(this.errorsFor(a)))},onfocusout:function(a){this.checkable(a)||!(a.name in this.submitted)&&this.optional(a)||this.element(a)},onkeyup:function(b,c){var d=[16,17,18,20,35,36,37,38,39,40,45,144,225];9===c.which&&""===this.elementValue(b)||a.inArray(c.keyCode,d)!==-1||(b.name in this.submitted||b.name in this.invalid)&&this.element(b)},onclick:function(a){a.name in this.submitted?this.element(a):a.parentNode.name in this.submitted&&this.element(a.parentNode)},highlight:function(b,c,d){"radio"===b.type?this.findByName(b.name).addClass(c).removeClass(d):a(b).addClass(c).removeClass(d)},unhighlight:function(b,c,d){"radio"===b.type?this.findByName(b.name).removeClass(c).addClass(d):a(b).removeClass(c).addClass(d)}},setDefaults:function(b){a.extend(a.validator.defaults,b)},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date (ISO).",number:"Please enter a valid number.",digits:"Please enter only digits.",equalTo:"Please enter the same value again.",maxlength:a.validator.format("Please enter no more than {0} characters."),minlength:a.validator.format("Please enter at least {0} characters."),rangelength:a.validator.format("Please enter a value between {0} and {1} characters long."),range:a.validator.format("Please enter a value between {0} and {1}."),max:a.validator.format("Please enter a value less than or equal to {0}."),min:a.validator.format("Please enter a value greater than or equal to {0}."),step:a.validator.format("Please enter a multiple of {0}.")},autoCreateRanges:!1,prototype:{init:function(){function b(b){!this.form&&this.hasAttribute("contenteditable")&&(this.form=a(this).closest("form")[0]);var c=a.data(this.form,"validator"),d="on"+b.type.replace(/^validate/,""),e=c.settings;e[d]&&!a(this).is(e.ignore)&&e[d].call(c,this,b)}this.labelContainer=a(this.settings.errorLabelContainer),this.errorContext=this.labelContainer.length&&this.labelContainer||a(this.currentForm),this.containers=a(this.settings.errorContainer).add(this.settings.errorLabelContainer),this.submitted={},this.valueCache={},this.pendingRequest=0,this.pending={},this.invalid={},this.reset();var c,d=this.groups={};a.each(this.settings.groups,function(b,c){"string"==typeof c&&(c=c.split(/\s/)),a.each(c,function(a,c){d[c]=b})}),c=this.settings.rules,a.each(c,function(b,d){c[b]=a.validator.normalizeRule(d)}),a(this.currentForm).on("focusin.validate focusout.validate keyup.validate",":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], [type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox'], [contenteditable]",b).on("click.validate","select, option, [type='radio'], [type='checkbox']",b),this.settings.invalidHandler&&a(this.currentForm).on("invalid-form.validate",this.settings.invalidHandler),a(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required","true")},form:function(){return this.checkForm(),a.extend(this.submitted,this.errorMap),this.invalid=a.extend({},this.errorMap),this.valid()||a(this.currentForm).triggerHandler("invalid-form",[this]),this.showErrors(),this.valid()},checkForm:function(){this.prepareForm();for(var a=0,b=this.currentElements=this.elements();b[a];a++)this.check(b[a]);return this.valid()},element:function(b){var c,d,e=this.clean(b),f=this.validationTargetFor(e),g=this,h=!0;return void 0===f?delete this.invalid[e.name]:(this.prepareElement(f),this.currentElements=a(f),d=this.groups[f.name],d&&a.each(this.groups,function(a,b){b===d&&a!==f.name&&(e=g.validationTargetFor(g.clean(g.findByName(a))),e&&e.name in g.invalid&&(g.currentElements.push(e),h=g.check(e)&&h))}),c=this.check(f)!==!1,h=h&&c,c?this.invalid[f.name]=!1:this.invalid[f.name]=!0,this.numberOfInvalids()||(this.toHide=this.toHide.add(this.containers)),this.showErrors(),a(b).attr("aria-invalid",!c)),h},showErrors:function(b){if(b){var c=this;a.extend(this.errorMap,b),this.errorList=a.map(this.errorMap,function(a,b){return{message:a,element:c.findByName(b)[0]}}),this.successList=a.grep(this.successList,function(a){return!(a.name in b)})}this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){a.fn.resetForm&&a(this.currentForm).resetForm(),this.invalid={},this.submitted={},this.prepareForm(),this.hideErrors();var b=this.elements().removeData("previousValue").removeAttr("aria-invalid");this.resetElements(b)},resetElements:function(a){var b;if(this.settings.unhighlight)for(b=0;a[b];b++)this.settings.unhighlight.call(this,a[b],this.settings.errorClass,""),this.findByName(a[b].name).removeClass(this.settings.validClass);else a.removeClass(this.settings.errorClass).removeClass(this.settings.validClass)},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(a){var b,c=0;for(b in a)a[b]&&c++;return c},hideErrors:function(){this.hideThese(this.toHide)},hideThese:function(a){a.not(this.containers).text(""),this.addWrapper(a).hide()},valid:function(){return 0===this.size()},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid)try{a(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(a){}},findLastActive:function(){var b=this.lastActive;return b&&1===a.grep(this.errorList,function(a){return a.element.name===b.name}).length&&b},elements:function(){var b=this,c={};return a(this.currentForm).find("input, select, textarea, [contenteditable]").not(":submit, :reset, :image, :disabled").not(this.settings.ignore).filter(function(){var d=this.name||a(this).attr("name");return!d&&b.settings.debug&&window.console&&console.error("%o has no name assigned",this),this.hasAttribute("contenteditable")&&(this.form=a(this).closest("form")[0]),!(d in c||!b.objectLength(a(this).rules()))&&(c[d]=!0,!0)})},clean:function(b){return a(b)[0]},errors:function(){var b=this.settings.errorClass.split(" ").join(".");return a(this.settings.errorElement+"."+b,this.errorContext)},resetInternals:function(){this.successList=[],this.errorList=[],this.errorMap={},this.toShow=a([]),this.toHide=a([])},reset:function(){this.resetInternals(),this.currentElements=a([])},prepareForm:function(){this.reset(),this.toHide=this.errors().add(this.containers)},prepareElement:function(a){this.reset(),this.toHide=this.errorsFor(a)},elementValue:function(b){var c,d,e=a(b),f=b.type;return"radio"===f||"checkbox"===f?this.findByName(b.name).filter(":checked").val():"number"===f&&"undefined"!=typeof b.validity?b.validity.badInput?"NaN":e.val():(c=b.hasAttribute("contenteditable")?e.text():e.val(),"file"===f?"C:\\fakepath\\"===c.substr(0,12)?c.substr(12):(d=c.lastIndexOf("/"),d>=0?c.substr(d+1):(d=c.lastIndexOf("\\"),d>=0?c.substr(d+1):c)):"string"==typeof c?c.replace(/\r/g,""):c)},check:function(b){b=this.validationTargetFor(this.clean(b));var c,d,e,f=a(b).rules(),g=a.map(f,function(a,b){return b}).length,h=!1,i=this.elementValue(b);if("function"==typeof f.normalizer){if(i=f.normalizer.call(b,i),"string"!=typeof i)throw new TypeError("The normalizer should return a string value.");delete f.normalizer}for(d in f){e={method:d,parameters:f[d]};try{if(c=a.validator.methods[d].call(this,i,b,e.parameters),"dependency-mismatch"===c&&1===g){h=!0;continue}if(h=!1,"pending"===c)return void(this.toHide=this.toHide.not(this.errorsFor(b)));if(!c)return this.formatAndAdd(b,e),!1}catch(a){throw this.settings.debug&&window.console&&console.log("Exception occurred when checking element "+b.id+", check the '"+e.method+"' method.",a),a instanceof TypeError&&(a.message+=".  Exception occurred when checking element "+b.id+", check the '"+e.method+"' method."),a}}if(!h)return this.objectLength(f)&&this.successList.push(b),!0},customDataMessage:function(b,c){return a(b).data("msg"+c.charAt(0).toUpperCase()+c.substring(1).toLowerCase())||a(b).data("msg")},customMessage:function(a,b){var c=this.settings.messages[a];return c&&(c.constructor===String?c:c[b])},findDefined:function(){for(var a=0;a<arguments.length;a++)if(void 0!==arguments[a])return arguments[a]},defaultMessage:function(b,c){"string"==typeof c&&(c={method:c});var d=this.findDefined(this.customMessage(b.name,c.method),this.customDataMessage(b,c.method),!this.settings.ignoreTitle&&b.title||void 0,a.validator.messages[c.method],"<strong>Warning: No message defined for "+b.name+"</strong>"),e=/\$?\{(\d+)\}/g;return"function"==typeof d?d=d.call(this,c.parameters,b):e.test(d)&&(d=a.validator.format(d.replace(e,"{$1}"),c.parameters)),d},formatAndAdd:function(a,b){var c=this.defaultMessage(a,b);this.errorList.push({message:c,element:a,method:b.method}),this.errorMap[a.name]=c,this.submitted[a.name]=c},addWrapper:function(a){return this.settings.wrapper&&(a=a.add(a.parent(this.settings.wrapper))),a},defaultShowErrors:function(){var a,b,c;for(a=0;this.errorList[a];a++)c=this.errorList[a],this.settings.highlight&&this.settings.highlight.call(this,c.element,this.settings.errorClass,this.settings.validClass),this.showLabel(c.element,c.message);if(this.errorList.length&&(this.toShow=this.toShow.add(this.containers)),this.settings.success)for(a=0;this.successList[a];a++)this.showLabel(this.successList[a]);if(this.settings.unhighlight)for(a=0,b=this.validElements();b[a];a++)this.settings.unhighlight.call(this,b[a],this.settings.errorClass,this.settings.validClass);this.toHide=this.toHide.not(this.toShow),this.hideErrors(),this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return a(this.errorList).map(function(){return this.element})},showLabel:function(b,c){var d,e,f,g,h=this.errorsFor(b),i=this.idOrName(b),j=a(b).attr("aria-describedby");h.length?(h.removeClass(this.settings.validClass).addClass(this.settings.errorClass),h.html(c)):(h=a("<"+this.settings.errorElement+">").attr("id",i+"-error").addClass(this.settings.errorClass).html(c||""),d=h,this.settings.wrapper&&(d=h.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()),this.labelContainer.length?this.labelContainer.append(d):this.settings.errorPlacement?this.settings.errorPlacement.call(this,d,a(b)):d.insertAfter(b),h.is("label")?h.attr("for",i):0===h.parents("label[for='"+this.escapeCssMeta(i)+"']").length&&(f=h.attr("id"),j?j.match(new RegExp("\\b"+this.escapeCssMeta(f)+"\\b"))||(j+=" "+f):j=f,a(b).attr("aria-describedby",j),e=this.groups[b.name],e&&(g=this,a.each(g.groups,function(b,c){c===e&&a("[name='"+g.escapeCssMeta(b)+"']",g.currentForm).attr("aria-describedby",h.attr("id"))})))),!c&&this.settings.success&&(h.text(""),"string"==typeof this.settings.success?h.addClass(this.settings.success):this.settings.success(h,b)),this.toShow=this.toShow.add(h)},errorsFor:function(b){var c=this.escapeCssMeta(this.idOrName(b)),d=a(b).attr("aria-describedby"),e="label[for='"+c+"'], label[for='"+c+"'] *";return d&&(e=e+", #"+this.escapeCssMeta(d).replace(/\s+/g,", #")),this.errors().filter(e)},escapeCssMeta:function(a){return a.replace(/([\\!"#$%&'()*+,./:;<=>?@\[\]^`{|}~])/g,"\\$1")},idOrName:function(a){return this.groups[a.name]||(this.checkable(a)?a.name:a.id||a.name)},validationTargetFor:function(b){return this.checkable(b)&&(b=this.findByName(b.name)),a(b).not(this.settings.ignore)[0]},checkable:function(a){return/radio|checkbox/i.test(a.type)},findByName:function(b){return a(this.currentForm).find("[name='"+this.escapeCssMeta(b)+"']")},getLength:function(b,c){switch(c.nodeName.toLowerCase()){case"select":return a("option:selected",c).length;case"input":if(this.checkable(c))return this.findByName(c.name).filter(":checked").length}return b.length},depend:function(a,b){return!this.dependTypes[typeof a]||this.dependTypes[typeof a](a,b)},dependTypes:{boolean:function(a){return a},string:function(b,c){return!!a(b,c.form).length},function:function(a,b){return a(b)}},optional:function(b){var c=this.elementValue(b);return!a.validator.methods.required.call(this,c,b)&&"dependency-mismatch"},startRequest:function(b){this.pending[b.name]||(this.pendingRequest++,a(b).addClass(this.settings.pendingClass),this.pending[b.name]=!0)},stopRequest:function(b,c){this.pendingRequest--,this.pendingRequest<0&&(this.pendingRequest=0),delete this.pending[b.name],a(b).removeClass(this.settings.pendingClass),c&&0===this.pendingRequest&&this.formSubmitted&&this.form()?(a(this.currentForm).submit(),this.formSubmitted=!1):!c&&0===this.pendingRequest&&this.formSubmitted&&(a(this.currentForm).triggerHandler("invalid-form",[this]),this.formSubmitted=!1)},previousValue:function(b,c){return c="string"==typeof c&&c||"remote",a.data(b,"previousValue")||a.data(b,"previousValue",{old:null,valid:!0,message:this.defaultMessage(b,{method:c})})},destroy:function(){this.resetForm(),a(this.currentForm).off(".validate").removeData("validator").find(".validate-equalTo-blur").off(".validate-equalTo").removeClass("validate-equalTo-blur")}},classRuleSettings:{required:{required:!0},email:{email:!0},url:{url:!0},date:{date:!0},dateISO:{dateISO:!0},number:{number:!0},digits:{digits:!0},creditcard:{creditcard:!0}},addClassRules:function(b,c){b.constructor===String?this.classRuleSettings[b]=c:a.extend(this.classRuleSettings,b)},classRules:function(b){var c={},d=a(b).attr("class");return d&&a.each(d.split(" "),function(){this in a.validator.classRuleSettings&&a.extend(c,a.validator.classRuleSettings[this])}),c},normalizeAttributeRule:function(a,b,c,d){/min|max|step/.test(c)&&(null===b||/number|range|text/.test(b))&&(d=Number(d),isNaN(d)&&(d=void 0)),d||0===d?a[c]=d:b===c&&"range"!==b&&(a[c]=!0)},attributeRules:function(b){var c,d,e={},f=a(b),g=b.getAttribute("type");for(c in a.validator.methods)"required"===c?(d=b.getAttribute(c),""===d&&(d=!0),d=!!d):d=f.attr(c),this.normalizeAttributeRule(e,g,c,d);return e.maxlength&&/-1|2147483647|524288/.test(e.maxlength)&&delete e.maxlength,e},dataRules:function(b){var c,d,e={},f=a(b),g=b.getAttribute("type");for(c in a.validator.methods)d=f.data("rule"+c.charAt(0).toUpperCase()+c.substring(1).toLowerCase()),this.normalizeAttributeRule(e,g,c,d);return e},staticRules:function(b){var c={},d=a.data(b.form,"validator");return d.settings.rules&&(c=a.validator.normalizeRule(d.settings.rules[b.name])||{}),c},normalizeRules:function(b,c){return a.each(b,function(d,e){if(e===!1)return void delete b[d];if(e.param||e.depends){var f=!0;switch(typeof e.depends){case"string":f=!!a(e.depends,c.form).length;break;case"function":f=e.depends.call(c,c)}f?b[d]=void 0===e.param||e.param:(a.data(c.form,"validator").resetElements(a(c)),delete b[d])}}),a.each(b,function(d,e){b[d]=a.isFunction(e)&&"normalizer"!==d?e(c):e}),a.each(["minlength","maxlength"],function(){b[this]&&(b[this]=Number(b[this]))}),a.each(["rangelength","range"],function(){var c;b[this]&&(a.isArray(b[this])?b[this]=[Number(b[this][0]),Number(b[this][1])]:"string"==typeof b[this]&&(c=b[this].replace(/[\[\]]/g,"").split(/[\s,]+/),b[this]=[Number(c[0]),Number(c[1])]))}),a.validator.autoCreateRanges&&(null!=b.min&&null!=b.max&&(b.range=[b.min,b.max],delete b.min,delete b.max),null!=b.minlength&&null!=b.maxlength&&(b.rangelength=[b.minlength,b.maxlength],delete b.minlength,delete b.maxlength)),b},normalizeRule:function(b){if("string"==typeof b){var c={};a.each(b.split(/\s/),function(){c[this]=!0}),b=c}return b},addMethod:function(b,c,d){a.validator.methods[b]=c,a.validator.messages[b]=void 0!==d?d:a.validator.messages[b],c.length<3&&a.validator.addClassRules(b,a.validator.normalizeRule(b))},methods:{required:function(b,c,d){if(!this.depend(d,c))return"dependency-mismatch";if("select"===c.nodeName.toLowerCase()){var e=a(c).val();return e&&e.length>0}return this.checkable(c)?this.getLength(b,c)>0:b.length>0},email:function(a,b){return this.optional(b)||/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(a)},url:function(a,b){return this.optional(b)||/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(a)},date:function(a,b){return this.optional(b)||!/Invalid|NaN/.test(new Date(a).toString())},dateISO:function(a,b){return this.optional(b)||/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(a)},number:function(a,b){return this.optional(b)||/^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(a)},digits:function(a,b){return this.optional(b)||/^\d+$/.test(a)},minlength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||e>=d},maxlength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||e<=d},rangelength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||e>=d[0]&&e<=d[1]},min:function(a,b,c){return this.optional(b)||a>=c},max:function(a,b,c){return this.optional(b)||a<=c},range:function(a,b,c){return this.optional(b)||a>=c[0]&&a<=c[1]},step:function(b,c,d){var e,f=a(c).attr("type"),g="Step attribute on input type "+f+" is not supported.",h=["text","number","range"],i=new RegExp("\\b"+f+"\\b"),j=f&&!i.test(h.join()),k=function(a){var b=(""+a).match(/(?:\.(\d+))?$/);return b&&b[1]?b[1].length:0},l=function(a){return Math.round(a*Math.pow(10,e))},m=!0;if(j)throw new Error(g);return e=k(d),(k(b)>e||l(b)%l(d)!==0)&&(m=!1),this.optional(c)||m},equalTo:function(b,c,d){var e=a(d);return this.settings.onfocusout&&e.not(".validate-equalTo-blur").length&&e.addClass("validate-equalTo-blur").on("blur.validate-equalTo",function(){a(c).valid()}),b===e.val()},remote:function(b,c,d,e){if(this.optional(c))return"dependency-mismatch";e="string"==typeof e&&e||"remote";var f,g,h,i=this.previousValue(c,e);return this.settings.messages[c.name]||(this.settings.messages[c.name]={}),i.originalMessage=i.originalMessage||this.settings.messages[c.name][e],this.settings.messages[c.name][e]=i.message,d="string"==typeof d&&{url:d}||d,h=a.param(a.extend({data:b},d.data)),i.old===h?i.valid:(i.old=h,f=this,this.startRequest(c),g={},g[c.name]=b,a.ajax(a.extend(!0,{mode:"abort",port:"validate"+c.name,dataType:"json",data:g,context:f.currentForm,success:function(a){var d,g,h,j=a===!0||"true"===a;f.settings.messages[c.name][e]=i.originalMessage,j?(h=f.formSubmitted,f.resetInternals(),f.toHide=f.errorsFor(c),f.formSubmitted=h,f.successList.push(c),f.invalid[c.name]=!1,f.showErrors()):(d={},g=a||f.defaultMessage(c,{method:e,parameters:b}),d[c.name]=i.message=g,f.invalid[c.name]=!0,f.showErrors(d)),i.valid=j,f.stopRequest(c,j)}},d)),"pending")}}});var b,c={};a.ajaxPrefilter?a.ajaxPrefilter(function(a,b,d){var e=a.port;"abort"===a.mode&&(c[e]&&c[e].abort(),c[e]=d)}):(b=a.ajax,a.ajax=function(d){var e=("mode"in d?d:a.ajaxSettings).mode,f=("port"in d?d:a.ajaxSettings).port;return"abort"===e?(c[f]&&c[f].abort(),c[f]=b.apply(this,arguments),c[f]):b.apply(this,arguments)})});

    if(!localStorage.fortunaLastView || localStorage.fortunaLastView < datetoday.getDate()) {
        if(window.frameCacheVars !== undefined) {
            BX.addCustomEvent("onFrameDataReceived" , function(json) {
                assemblyCircle();
                setTimeout(show, 10000);
            });
        }else {
            assemblyCircle();
            setTimeout(show, 10000);
        }
    }

    function createPopup(){
        return BX.PopupWindowManager.create("popup-fortuna-extremelook", null, {
            content: BX('extremefortuna-container'),
            zIndex: 100, // z-index
            closeIcon: null,
            titleBar: false,
            height: 500,
            overflow: false,
            closeByEsc: true, // закрытие окна по esc
            darkMode: false, // окно будет светлым или темным
            autoHide: true, // закрытие при клике вне окна
            draggable: false, // можно двигать или нет
            resizable: false, // можно ресайзить
            lightShadow: false, // использовать светлую тень у окна
            angle: false, // появится уголок
            overlay: {
                backgroundColor: 'black',
                opacity: 500
            },
            events: {
                onPopupShow: function () {

                },
                onPopupClose: function () {
                    localStorage.fortunaLastView = datetoday.getDate(); //При закрытии окна загоняем дату в браузер чтобы потом ориентироваться
                }
            }
        });
    }
    function show(){ //Стартуем
        var popup = createPopup(),
            contentContainer = BX('extremefortuna-container');

        BX.bind(contentContainer.querySelector('#extremefortuna-close'), 'click', function () {
            popup.close();
        });
        BX.bind(contentContainer.querySelector('#button_by_catalog'), 'click', function () {
            popup.close();
        });
        popup.show();
    };

    function assemblyCircle() {
        const ajax = "/bitrix/components/altop/fortuna.vlad/templates/.default/ajax/ajax.php";
        const paramColor = {
            'firstColor': $(popupContainerBX).hasClass('t-fortuna_default') ? '#cbbdf8' : ($(popupContainerBX).hasClass('t-fortuna_winter') || $(popupContainerBX).hasClass('t-fortuna_spring')) ? '#6e7bf9' : '#cbbdf8',
            'secondColor': $(popupContainerBX).hasClass('t-fortuna_default') ? '#b6aff9' : ($(popupContainerBX).hasClass('t-fortuna_winter') || $(popupContainerBX).hasClass('t-fortuna_spring')) ? '#3fa9f6' : '#b6aff9'
        }
        const prizes = [
            {
                text: "Ресницы в подарок",
                color: paramColor.firstColor,
                gift: 183, //ИД правила
                description: "Выбирай мини - версию ресниц 2 линии при заказе от 1000р"
            },
            {
                text: "Скидка 500р",
                color: paramColor.secondColor,
                gift: 181, //ИД правила
                description: "Оплатим 500 руб из твоего чека при заказе от 2900р!\nСкидка не распространяется на товары из раздела 'Освещение' и 'Косметологические кушетки'"
            },
            {
                text: "Скидка 15%",
                color: paramColor.firstColor,
                gift: 180, //ИД правила
                description: "Получи скидку 15% на любые выбранные тобой товары при заказе от 2000р\nСкидка не распространяется на товары из раздела 'Освещение' и 'Косметологические кушетки'"
            },
            {
                text: "Пинцет в подарок",
                color: paramColor.secondColor,
                gift: 182, //ИД правила
                description: "Оформи заказ от 3500р – и получи пинцет Бесплатно!*\nинструмент из категории Classic Blend, кроме L Premium"
            },
            {
                text: "Ресницы в подарок",
                color: paramColor.firstColor,
                gift: 183, //ИД правила
                description: "Выбирай мини - версию ресниц 2 линии при заказе от 1000р"
            },
            {
                text: "Скидка 500р",
                color: paramColor.secondColor,
                gift: 181, //ИД правила
                description: "Оплатим 500 руб из твоего чека при заказе от 2900 руб!\nСкидка не распространяется на товары из раздела 'Освещение' и 'Косметологические кушетки'"
            },
            {
                text: "Скидка 15%",
                color: paramColor.firstColor,
                gift: 180, //ИД правила
                description: "Получи скидку 15% на любые выбранные тобой товары при заказе от 2000р\nСкидка не распространяется на товары из раздела 'Освещение' и 'Косметологические кушетки'"
            },
            {
                text: "Пинцет в подарок",
                color: paramColor.secondColor,
                gift: 182, //ИД правила
                description: "Оформи заказ от 3500р – и получи пинцет Бесплатно!*\nинструмент из категории Classic Blend, кроме L Premium"
            },
        ];

        // создаём переменные для быстрого доступа ко всем объектам на странице — блоку в целом, колесу, кнопке и язычку
        const wheel = document.querySelector(".deal-wheel");
        const spinner = wheel.querySelector(".spinner");
        const exForm = document.getElementById('extremefortuna-form');
        const mainBlock = exForm.querySelector('#ex-main');
        const blockThank = exForm.querySelector('#ex-thankyou');
        const trigger = exForm.querySelector(".btn-spin");
        const form = document.getElementById('extremefortuna-form-form');
        const formInputName = form.querySelector('#name_fort');
        const formInputMail = form.querySelector('#email_fort');
        const formInputPhone = form.querySelector('#phone_fort');
        //const ticker = wheel.querySelector(".ticker");

        // на сколько секторов нарезаем круг
        const prizeSlice = 360 / prizes.length;
        // на какое расстояние смещаем сектора друг относительно друга
        const prizeOffset = Math.floor(180 / prizes.length);
        // прописываем CSS-классы, которые будем добавлять и убирать из стилей
        const spinClass = "is-spinning";
        const selectedClass = "selected";
        // получаем все значения параметров стилей у секторов
        const spinnerStyles = window.getComputedStyle(spinner);

        //где язычок выбора (угол)
        if ($(window).width() < '991')
            var selectionAngle = 90; //Для мобилок язычок сверху
        else
            var selectionAngle = 180; //Для десктопа язычок справа

        // переменная для анимации
        let tickerAnim;
        // угол вращения
        let rotation = 0;
        // текущий сектор
        let currentSlice = 0;
        // переменная для текстовых подписей
        let prizeNodes;

        // расставляем текст по секторам
        const createPrizeNodes = () => {
            // обрабатываем каждую подпись
            prizes.forEach(({ text }, i) => {
                // каждой из них назначаем свой угол поворота
                const rotation = ((prizeSlice * i) * -1) - prizeOffset + selectionAngle;
                // добавляем код с размещением текста на страницу в конец блока spinner
                spinner.insertAdjacentHTML(
                    "beforeend",
                    // текст при этом уже оформлен нужными стилями
                    `<li class="prize" style="--rotate: ${rotation}deg">
                <span class="text" style="transform: rotate(180deg);">${text}</span>
              </li>`
                );
            });
        };

        // рисуем разноцветные секторы
        const createConicGradient = () => {
            // устанавливаем нужное значение стиля у элемента spinner
            spinner.setAttribute(
                "style",
                `background: conic-gradient(
              from -90deg,
              ${prizes
                // получаем цвет текущего сектора
                    .map(({ color }, i) => `${color} 0 ${(100 / prizes.length) * (prizes.length - i)}%`)
                    .reverse()
                    }
            ); border: 12px solid white;`
            );
        };

        // создаём функцию, которая нарисует колесо в сборе
        const setupWheel = () => {
            // сначала секторы
            createConicGradient();
            // потом текст
            createPrizeNodes();
            // а потом мы получим список всех призов на странице, чтобы работать с ними как с объектами
            prizeNodes = wheel.querySelectorAll(".prize");
        };

        // определяем количество оборотов, которое сделает наше колесо
        const spinertia = (min, max) => {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        };

        // функция запуска вращения с плавной остановкой
        const runTickerAnimation = () => {
            // взяли код анимации отсюда: https://css-tricks.com/get-value-of-css-rotation-through-javascript/
            const values = spinnerStyles.transform.split("(")[1].split(")")[0].split(",");
            const a = values[0];
            const b = values[1];
            let rad = Math.atan2(b, a);

            if (rad < 0) rad += (2 * Math.PI);

            const angle = Math.round(rad * (180 / Math.PI));
            const slice = Math.floor(angle / prizeSlice);

            // анимация язычка, когда его задевает колесо при вращении
            // если появился новый сектор
            /*if (currentSlice !== slice) {
                // убираем анимацию язычка
                ticker.style.animation = "none";
                // и через 10 миллисекунд отменяем это, чтобы он вернулся в первоначальное положение
                setTimeout(() => ticker.style.animation = null, 10);
                // после того, как язычок прошёл сектор - делаем его текущим
                currentSlice = slice;
            }*/
            // запускаем анимацию
            tickerAnim = requestAnimationFrame(runTickerAnimation);
        };

        // функция выбора призового сектора и генерация купона
        const selectPrize = () => {
            const selected = Math.floor(rotation / prizeSlice);
            var tel = formInputPhone.value;
            tel = tel.replace(/[^0-9]/gi, '');

            var inputsData = {
                name: formInputName.value,
                tel: tel,
                mail: formInputMail.value
            };
            prizeNodes[selected].classList.add(selectedClass);

            $.post(ajax, {action: "addCouponContact", dataGift: prizes[selected], dataUser: inputsData}, function(data){
                var dataJSON = JSON.parse(data);
                if(!dataJSON.error){
                    mainBlock.style.display = 'none';
                    blockThank.style.display = 'block';
                }
            })
                .fail(function(data) {
                    console.log("error" + JSON.stringify(data, true, 4));
                });

        };

        // отслеживаем нажатие на кнопку
        trigger.addEventListener("click", () => {
            // делаем её недоступной для нажатия
            trigger.disabled = true;
            formInputMail.disabled = true;
            formInputName.disabled = true;
            formInputPhone.disabled = true;
            // задаём начальное вращение колеса
            rotation = Math.floor(Math.random() * 360 + spinertia(4000, 5000));
            // убираем прошлый приз
            prizeNodes.forEach((prize) => prize.classList.remove(selectedClass));
            // добавляем колесу класс is-spinning, с помощью которого реализуем нужную отрисовку
            wheel.classList.add(spinClass);
            // через CSS говорим секторам, как им повернуться
            spinner.style.setProperty("--rotate", rotation);
            // возвращаем язычок в горизонтальную позицию
            //ticker.style.animation = "none";
            // запускаем анимацию вращение
            runTickerAnimation();
        });

        // отслеживаем, когда закончилась анимация вращения колеса
        spinner.addEventListener("transitionend", () => {
            // останавливаем отрисовку вращения
            cancelAnimationFrame(tickerAnim);
            // получаем текущее значение поворота колеса
            rotation %= 360;
            // выбираем приз
            selectPrize();
            // убираем класс, который отвечает за вращение
            wheel.classList.remove(spinClass);
            // отправляем в CSS новое положение поворота колеса
            spinner.style.setProperty("--rotate", rotation);
            // делаем кнопку снова активной
            trigger.disabled = false;
        });

        // подготавливаем всё к первому запуску
        setupWheel();

        jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^((8|\+7|380|\+380|39|\+39|48|\+48|49|\+49)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/);
        }, "Ошибка формата телефона");

        $("#extremefortuna-form-form").on("keyup keypress blur change", 'input', function(){
            var tel = $('#phone_fort').val();
            tel = tel.replace(/[^0-9]/gi, '');

            if($("#extremefortuna-form-form").valid()) {
                $.post(ajax, {
                    action: "checkEmail",
                    dataFilter: {mail: $('#email_fort').val(), phone: tel}
                }, function (data) {
                    var dataJSON = JSON.parse(data);
                    if (!dataJSON.result) {
                        $('#phone_fort-error').text('').hide();
                        $('#sumbit_fortuna').show();
                    } else {
                        $('#phone_fort-error').text('Данный email или телефон уже был использован ранее').show();
                        $('#sumbit_fortuna').hide();
                    }
                });
            }
            else
                $('#sumbit_fortuna').hide();
        });

        $('form#extremefortuna-form-form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                email: {
                    minlength: 4,
                    maxlength: 50,
                    email: true,
                    required: true
                },
                phone: {
                    minlength: 7,
                    maxlength: 18,
                    required: true,
                    phoneUS: true
                },
                check: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Поле 'Имя' обязательно к заполнению",
                    minlength: "Введите не менее 2-х символов в поле 'Имя'",
                    maxlength: "Максимальное количество символов в поле - 50"
                },
                email: {
                    required: "Поле 'Email' обязательно к заполнению",
                    email: "Необходим формат адреса email",
                    maxlength: "Максимальное количество символов в поле - 50",
                    minlength: "Введите не менее 4-х символов в поле 'Email'"
                },
                phone: {
                    required: "Поле 'Телефон' обязательно к заполнению",
                    minlength: "Минимальное количество символов - 11",
                    maxlength: "Максимальное количество символов - 18"
                },
                check: {
                    required: "Подтвердите соглашение"
                }
            }
        });
    }
})();