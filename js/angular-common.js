



app .value('API_URL_BASE', 'http://l.h/djland-dev/api')

    .factory('apiService', function ($http, API_URL_BASE) {
      return {


        getShowData: function (id) {
          return $http.get(API_URL_BASE + '/show?ID=' + id);
        },

        saveShowData: function (data) {
          return $http.post(API_URL_BASE + '/show/save.php', data);
        }


      };
    });


