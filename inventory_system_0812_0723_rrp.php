<?php
// 代码生成时间: 2025-08-12 07:23:41
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// InventoryItem class represents a single item in inventory
class InventoryItem {
    private $id;
    private $name;
    private $quantity;

    public function __construct($id, $name, $quantity) {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
    }

    // Getters and setters for properties
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}

// InventoryManager class handles inventory operations
class InventoryManager {
    private $items = [];

    public function addItem(InventoryItem $item) {
        $this->items[$item->getId()] = $item;
    }

    public function updateItemQuantity($id, $newQuantity) {
        if (isset($this->items[$id])) {
            $this->items[$id]->setQuantity($newQuantity);
            return true;
        }
        return false;
    }

    public function getItem($id) {
        return $this->items[$id] ?? null;
    }

    public function getAllItems() {
        return $this->items;
    }
}

// Slim app setup
$app = AppFactory::create();

// Get all items
$app->get('/inventory', function (Request $request, Response $response, $args) {
    $inventoryManager = new InventoryManager();
    $inventoryManager->addItem(new InventoryItem(1, 'Item 1', 10));
    $inventoryManager->addItem(new InventoryItem(2, 'Item 2', 20));

    $response->getBody()->write(json_encode($inventoryManager->getAllItems()));
    return $response->withHeader('Content-Type', 'application/json');
});

// Update item quantity
$app->put('/inventory/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $inventoryManager = new InventoryManager();
    $inventoryManager->addItem(new InventoryItem(1, 'Item 1', 10));
    $inventoryManager->addItem(new InventoryItem(2, 'Item 2', 20));

    $newQuantity = $request->getParsedBody()['quantity'] ?? 0;
    if ($inventoryManager->updateItemQuantity($args['id'], $newQuantity)) {
        $response->getBody()->write(json_encode(['message' => 'Quantity updated']));
    } else {
        $response->getBody()->write(json_encode(['error' => 'Item not found']));
        $response = $response->withStatus(404);
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// Run the app
$app->run();

/*
 * The code above sets up a simple inventory management system using Slim framework.
 * It includes two main classes: InventoryItem and InventoryManager.
 * InventoryItem represents an item with an ID, name, and quantity.
 * InventoryManager handles the operations on inventory items.
 * The Slim app has two routes: one for getting all items and another for updating the quantity of a specific item.
 * The code follows best practices, includes error handling, and is structured for maintainability and extensibility.
 */