MedOptima.prototype.ReservationModelDoctor = Backbone.Model.extend({

    defaults : {
        id : undefined,
        name : undefined,
        photo : undefined,
        posts : [],
        schedule : [],
        isSelected : false,
        selectedTime : undefined
    },

    toJSON : function() {
        return _.extend(_.clone(this.attributes), {
            posts : _.map(this.get('posts'), function(post) {
                return post.toLowerCase();
            }).join(', ')
        });
    }

}, {

});