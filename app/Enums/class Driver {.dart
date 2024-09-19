class Driver {
  final String name;
  final Truck truck;

  factory Driver.fromJson(Map<String, dynamic> json) {
    return Driver(
      name: json['name'],
      truck: Truck.fromJson(json['truck']),
    );
  }
}

class Truck {
  final String plate;
  //...
}

class Customer {
  final String name;
  final String address;
}

class Review {
  final String comment;
  final int rating;
}

class Order {
  final Customer customer;
  final Driver driver;
  final Truck truck;
  final List<Review> reviews;

  final String orderno;

  factory Order.fromJson(Map<String, dynamic> json) {
    return Order(
      customer: json['customer'] ? Customer.fromJson(json['customer']) : null,
      driver: Driver.fromJson(json['driver']),
      truck: Truck.fromJson(json['truck']),
      reviews: json['reviews']
          .map<Review>((review) => Review.fromJson(review))
          .toList(),
      orderno: json['orderno'],
    );
  }
}