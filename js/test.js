//Testing Code
var testing = true;



function assertTrue(condition,message){
	if(testing){
		if(!condition){
		var message = message || "Assertion Failed";
			if(typeof Error !== "undefined"){
				throw new Error(message);
			}else{
				throw message;
			}
		}
	}
	
}

function assertEqual(value,expected_value,desc){
	if(testing){
		var message = desc;
		if(typeof value != typeof expected_value){
			message += ":Type Mismatch";	
		}else if(value == null || expected_value == null){
			message += ":A variable is null";
		}else if(value != expected_value){
			message += ":Value "+value+" /="+expected_value;
		}
		if(typeof Error !== "undefined"){
			throw new Error(message);
		}else{
			throw message;
		}
	}
}



