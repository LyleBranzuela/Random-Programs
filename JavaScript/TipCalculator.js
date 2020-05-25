// Exercise from a Udemy Tutorial https://www.udemy.com/course/the-complete-javascript-course/
var john = {
    fullName: 'John Smith',
    bills: [124, 48, 268, 180, 42],

    calculateTips: function() {
        var tips = [];
        for (var counter = 0; counter < this.bills.length; counter++) {
            currentBill = this.bills[counter];
            if (currentBill < 50) {
                tips.push(0.2 * currentBill);
            } else if (currentBill >= 50 && currentBill <= 200) {
                tips.push(0.15 * currentBill);
            } else if (currentBill > 200) {
                tips.push(0.10 * currentBill);
            }
        }
        return tips;
    },

    calculateFinalPaid: function() {
        var tips = this.calculateTips();
        calculatedPayments = [];
        for (var counter = 0; counter < this.bills.length; counter++) {
            calculatedPayments.push(tips[counter] + this.bills[counter]);
        }
        return calculatedPayments;
    }
}

var mark = {
    fullName: 'Mark Smith',
    bills: [77, 375, 110, 45],

    calculateTips: function() {
        var tips = [];
        for (var counter = 0; counter < this.bills.length; counter++) {
            currentBill = this.bills[counter];
            if (currentBill < 100) {
                tips.push(0.2 * currentBill);
            } else if (currentBill >= 100 && currentBill <= 300) {
                tips.push(0.10 * currentBill);
            } else if (currentBill > 300) {
                tips.push(0.25 * currentBill);
            }
        }
        return tips;
    },

    calculateFinalPaid: function() {
        var tips = this.calculateTips();
        calculatedPayments = [];
        for (var counter = 0; counter < this.bills.length; counter++) {
            calculatedPayments.push(tips[counter] + this.bills[counter]);
        }
        return calculatedPayments;
    }
}

function calculateAverage(tips) {
    var averageTips = 0;
    for (var counter = 0; counter < tips.length; counter++) {
        averageTips += tips[counter];
    }
    averageTips /= tips.length;

    return averageTips;
}


console.log("\n===============================================================");
console.log("                             John                                ");
console.log("===============================================================");
console.log("Tips: " + john.calculateTips());
console.log("Final Paid: " + john.calculateFinalPaid());
console.log("Averagely Paid: " + calculateAverage(john.calculateTips()));

console.log("\n===============================================================");
console.log("                             Mark                                ");
console.log("===============================================================");
console.log("Tips:" + mark.calculateTips());
console.log("Final Paid: " + mark.calculateFinalPaid());
console.log("Averagely Paid: " + calculateAverage(mark.calculateTips()));