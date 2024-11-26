src/main/java/com/shop
- controller
- ProductController.java
- UserController.java
- OrderController.java
- model
- Product.java
- User.java
- Order.java
- repository
- ProductRepository.java
- UserRepository.java
- OrderRepository.java
- service
- ProductService.java
- UserService.java
- OrderService.java
- Application.java

spring.datasource.url=jdbc:mysql://localhost:3306/shop_db
spring.datasource.username=root
spring.datasource.password=your_password

spring.jpa.hibernate.ddl-auto=update
spring.jpa.show-sql=true
spring.jpa.properties.hibernate.dialect=org.hibernate.dialect.MySQL5Dialect


package com.shop.model;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Data
@NoArgsConstructor
@AllArgsConstructor
public class Product {
@Id
@GeneratedValue(strategy = GenerationType.IDENTITY)
private Long id;

private String name;
private String description;
private double price;
private int stock;
}

package com.shop.model;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Data
@NoArgsConstructor
@AllArgsConstructor
public class User {
@Id
@GeneratedValue(strategy = GenerationType.IDENTITY)
private Long id;

private String username;
private String email;
private String password;
}

package com.shop.model;

import jakarta.persistence.*;
import lombok.*;
import java.util.*;

@Entity
@Data
@NoArgsConstructor
@AllArgsConstructor
public class Order {
@Id
@GeneratedValue(strategy = GenerationType.IDENTITY)
private Long id;

@ManyToOne
private User user;

@ManyToMany
private List<Product> products = new ArrayList<>();

private String status; // PENDING, SHIPPED, CANCELLED
}

package com.shop.repository;

import com.shop.model.Product;
import org.springframework.data.jpa.repository.JpaRepository;

public interface ProductRepository extends JpaRepository<Product, Long> {
}

package com.shop.repository;

import com.shop.model.User;
import org.springframework.data.jpa.repository.JpaRepository;

public interface UserRepository extends JpaRepository<User, Long> {
User findByUsername(String username);
}

package com.shop.repository;

import com.shop.model.Order;
import org.springframework.data.jpa.repository.JpaRepository;

public interface OrderRepository extends JpaRepository<Order, Long> {
}

package com.shop.service;

import com.shop.model.Product;
import com.shop.repository.ProductRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class ProductService {
@Autowired
private ProductRepository productRepository;

public List<Product> getAllProducts() {
return productRepository.findAll();
}

public Product getProductById(Long id) {
return productRepository.findById(id).orElse(null);
}

public Product saveProduct(Product product) {
return productRepository.save(product);
}

public void deleteProduct(Long id) {
productRepository.deleteById(id);
}
}



package com.shop.controller;

import com.shop.model.Product;
import com.shop.service.ProductService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/products")
public class ProductController {
@Autowired
private ProductService productService;

@GetMapping
public List<Product> getAllProducts() {
return productService.getAllProducts();
}

@GetMapping("/{id}")
public Product getProductById(@PathVariable Long id) {
return productService.getProductById(id);
}

@PostMapping
public Product saveProduct(@RequestBody Product product) {
return productService.saveProduct(product);
}

@DeleteMapping("/{id}")
public void deleteProduct(@PathVariable Long id) {
productService.deleteProduct(id);
}
}

package com.shop;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
public class Application {
public static void main(String[] args) {
SpringApplication.run(Application.class, args);
}
}

• Obtenir tous les produits :
GET http://localhost:8080/api/products
• Ajouter un produit :
POST http://localhost:8080/api/products
Body (JSON) :

{
"name": "Ordinateur portable",
"description": "Un excellent ordinateur portable",
"price": 1500.0,
"stock": 10
}


• Supprimer un produit :
DELETE http://localhost:8080/api/products/{id}






