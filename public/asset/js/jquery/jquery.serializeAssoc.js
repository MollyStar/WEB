/**
 * Created by shinate on 2017/9/8.
 */

jQuery.fn.extend({
    serializeAssoc: function () {
        var ob = {};
        jQuery.each(this.serializeArray(), function (_, item) {
            ob[item.name] = item.value;
        });
        return ob;
    }
});