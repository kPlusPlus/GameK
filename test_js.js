var Person = function (name) {
	this.name = name;
}


var john = new Person("John");
alert(Person.prototype);      	// => ...?
//alert(john);        			// => ...?