//Tests the membership submissions page

//Load the page and
QUnit.test("Load Test", function(assert){
	getConstants();
	add_submission_handlers();
	assert.equal("Test","Test");
});
