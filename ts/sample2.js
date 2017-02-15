function greeter(person) {
    var ime = person.firstName + " " + person.lastName;
    return "Hello, " + ime;
}
var user = { firstName: "Jane", lastName: "User" };
document.body.innerHTML = greeter(user);
