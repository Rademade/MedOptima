(function(){

    var Good = function(idGood) {

    };

	AdminkaWork.prototype.Good = (function(idGood){
        if (idGood === undefined) {
            idGood = 0;
        }
        return new Good(idGood);
    });

})();