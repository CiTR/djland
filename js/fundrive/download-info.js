(function (){
	var app = angular.module('djland.open_fundrive',['djland.api']);

	app.controller('fundriveDump',function(call){

		this.load = function(){
			call.getForms().then(function(response){
				output = csv(response.data); //csv data is in output array
				var date = new Date();
				if (window.navigator.msSaveOrOpenBlob) {
					var blob = new Blob([output]);  //csv data string as an array.
					// IE hack; see http://msdn.microsoft.com/en-us/library/ie/hh779016.aspx
					window.navigator.msSaveBlob(blob, fileName);
				} else {
					var anchor = angular.element('<a/>');
					anchor.css({display: 'none'}); // Make sure it's not visible
					angular.element(document.body).append(anchor); // Attach to document for FireFox
					anchor.attr({
						href: 'data:attachment/csv;charset=utf-8,' + encodeURI(output),
						target: '_blank',
						download: 'fundriveDump-'+
						date.getFullYear() + '-' +
						((date.getMonth()+1).toString().length == '1' ? '0'+ (date.getMonth()+1): (date.getMonth()+1)) + '-' +
						(date.getDate().toString().length == 1 ? '0' + date.getDate() : date.getDate()) + '.csv'
					})[0].click();
					console.log(date.getMonth().toString().length);
					anchor.remove();
				}
			});
		}
		this.load();
		});
})();

function go(element){
	href = element.getAttribute('data-href');
	window.document.location = href;
}

function csv(arr) {
    var ret = [];
    ret.push('"' + Object.keys(arr[0]).join('","') + '"');
    for (var i = 0, len = arr.length; i < len; i++) {
        var line = [];
        for (var key in arr[i]) {
            if (arr[i].hasOwnProperty(key)) {
                line.push('"' + arr[i][key] + '"');
            }
        }
        ret.push(line.join(','));
    }
    return ret.join('\n');
}
