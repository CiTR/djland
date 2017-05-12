(function () {
    var app = angular.module('djland.report', ['djland.api', 'ui.bootstrap']);

    app.controller('reportController', function (call, $filter, $scope) {
        $scope.Object = Object;
        this.show_filter = 'all';
        var date = new Date();
        this.to = $filter('date')(date, 'yyyy/MM/dd');
        this.from = $filter('date')(date.setDate(date.getDate() - 1), 'yyyy/MM/dd');
        this.member_id = $('#member_id').text();
        this.show_names = Array();
        this.type = 'crtc';
        var this_ = this;
        this.loading = true;
        this.show_count = 0;
        this.init = function () {
            //Initial loading requests
            call.getMemberShows(this.member_id).then(
                (function (response) {
                    this.shows = response.data.shows;
                }).bind(this)
            );
            call.isStaff(this.member_id).then(
                (function (response) {
                    this.is_staff = response.data == true ? true : false;
                }).bind(this),
                function (error) {

                }
            );
            this.report();
        }
        this.report = function () {
            this_ = this;
            $('#report_summary').addClass('invisible');
            $('#report_list').addClass('invisible');
            this.loading = true;
            call.getReport(this.show_filter, $filter('date')(this.from, 'yyyy/MM/dd'), $filter('date')(this.to, 'yyyy/MM/dd'), this.type).then(
                function (response) {
                    this_.playsheets = response.data.playsheets.length > 0 ? angular.copy(response.data.playsheets) : Array();
                    this_.totals = response.data.totals;
                    this_.loading = false;
                    //delay displaying so to reduce lag from object creation.
                    setTimeout(function () {
                        if (this_.playsheets.length > 0) {
                            $('#report_summary').removeClass('invisible');
                            $('#report_list').removeClass('invisible');
                        }
                    }, 1000);
                },
                function (error) {
                    alert("Please try disabling adblock");
                }
            );
        }
        this.toggle_print = function (element) {
            var button = $('#print_friendly');
            var button_holder = $('.print_button');
            if (button.text() == "Print Friendly View") {
                button.text("Normal View");
                button_holder.addClass('text-center');
                $('#nav, #filter_bar').hide();
                $('body').removeClass('wallpaper');
                $('.red').addClass('lightgrey');
                $('.red').toggleClass('red');
                $('.crtc_report').addClass('print_wrapper');
            } else {
                button.text("Print Friendly View");
                $('#nav, #filter_bar').show();
                button_holder.removeClass('text-center');
                $('body').addClass('wallpaper');
                $('.crtc_report').removeClass('print_wrapper');
                $('.lightgrey').addClass('red');
                $('.lightgrey').toggleClass('lightgrey');
            }
        }
        this.init();
    });

    app.controller('datepicker', function ($filter) {
        this.today = function () {
            this.dt = $filter('date')(new Date(), 'yyyy/MM/dd');
        };
        this.clear = function () {
            this.dt = null;
        };
        this.open = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            this.opened = true;
        };
        this.format = 'yyyy-MM-dd';
    });
    app.directive('reportitem', function () {
        return {
            restrict: 'A',
            templateUrl: 'templates/report_item.html'
        }
    });
    app.filter('pad', function () {
        return function (n, len) {
            var num = parseInt(n, 10);
            len = parseInt(len, 10);
            if (isNaN(num) || isNaN(len)) {
                return n;
            }
            num = '' + num;
            while (num.length < len) {
                num = '0' + num;
            }
            return num;
        };
    });
    app.filter('percentage', ['$filter', function ($filter) {
        return function (input, decimals) {
            return $filter('number')(input * 100, decimals) + '%';
        };
    }]);

})();
