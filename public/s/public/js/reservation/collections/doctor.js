MedOptima.prototype.ReservationDoctorCollection = Backbone.Collection.extend({

    model : MedOptima.prototype.ReservationModelDoctor,
    url : '/doctor/list/ajax' //RM_TODO from server. Can move to config

}, {

});