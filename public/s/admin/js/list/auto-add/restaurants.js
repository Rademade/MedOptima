(function(){

    var restaurants = function() {

        this.getRowHtml = function(data, saveData) {
            return ['<tr id="row_', saveData.idRecommendedRestaurant, '" data-id="', saveData.idRecommendedRestaurant, '">',
                '<td class="first">',
                    '<label for="check_', saveData.idRecommendedRestaurant, '">',
                        '<b>', saveData.value, '</b>',
                    '</label>',
                '</td>',
                '<td>',
                    '<p class="item-control">',
                        '<a href="javascript:void(0);" title="Удалить" class="delete-link" onclick="Adminka.Item(',
                            saveData.idRecommendedRestaurant,
                        ').drop();">',
                        '</a>',
                    '</p>',
                '</td>',
            '</tr>'].join('');
        };

    };

    AdminkaWork.prototype.addStrategy.restaurants = function() {
        return new restaurants();
    };

})();