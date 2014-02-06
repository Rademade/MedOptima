MedOptima.prototype.ReservationModelDoctor = Backbone.Model.extend({

    attributes : {
        id : undefined,
        name : undefined,
        photo : undefined,
        posts : [],
        schedule : []
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