MedOptima.prototype.CalendarDateCollection = Backbone.Collection.extend({

    model : MedOptima.prototype.CalendarDateModel,

    findByDate : function(date) {
        return this.filter(function(dateModel) {
            return dateModel.getDate().isEqual(date);
        })[0];
    }

});