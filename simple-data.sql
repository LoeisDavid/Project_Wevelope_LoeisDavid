
-- INSERT INTO customers
INSERT INTO customers (REF_NO, NAME) VALUES
('CUST001', 'John Doe'),
('CUST002', 'Jane Smith'),
('CUST003', 'Michael Johnson'),
('CUST004', 'Emily Davis'),
('CUST005', 'William Brown'),
('CUST006', 'Linda Martinez'),
('CUST007', 'Robert Wilson'),
('CUST008', 'Patricia Taylor'),
('CUST009', 'David Anderson'),
('CUST010', 'Barbara Thomas');

-- INSERT INTO items
INSERT INTO items (REF_NO, NAME, PRICE) VALUES
('ITEM001', 'Microphone', 250.00),
('ITEM002', 'Speaker', 500.00),
('ITEM003', 'Monitor', 750.00),
('ITEM004', 'Mixer', 1200.00),
('ITEM005', 'Amplifier', 980.00),
('ITEM006', 'LED Light', 300.00),
('ITEM007', 'Couch', 1500.00),
('ITEM008', 'TV', 2000.00),
('ITEM009', 'Remote', 80.00),
('ITEM010', 'Controller', 150.00);

-- INSERT INTO suppliers
INSERT INTO suppliers (REF_NO, NAME) VALUES
('SUP001', 'Alpha Supply Co.'),
('SUP002', 'Beta Electronics'),
('SUP003', 'Gamma Logistics'),
('SUP004', 'Delta Distributors'),
('SUP005', 'Epsilon Industries'),
('SUP006', 'Zeta Traders'),
('SUP007', 'Eta Global'),
('SUP008', 'Theta Hardware'),
('SUP009', 'Iota Systems'),
('SUP010', 'Kappa Wholesale');

-- INSERT INTO invoice
INSERT INTO invoice (KODE, DATE, CUSTOMER_ID) VALUES
('INV001', '2025-04-01', 1),
('INV002', '2025-04-02', 2),
('INV003', '2025-04-03', 3),
('INV004', '2025-04-04', 4),
('INV005', '2025-04-05', 5),
('INV006', '2025-04-06', 6),
('INV007', '2025-04-07', 7),
('INV008', '2025-04-08', 8),
('INV009', '2025-04-09', 9),
('INV010', '2025-04-10', 10);

-- INSERT INTO iteminv
INSERT INTO iteminv (INVOICE_ID, ITEM_ID, QTY, PRICE, TOTAL) VALUES
(1, 1, 2, 250.00, 500.00),
(2, 2, 1, 500.00, 500.00),
(3, 3, 3, 750.00, 2250.00),
(4, 4, 1, 1200.00, 1200.00),
(5, 5, 2, 980.00, 1960.00),
(6, 6, 4, 300.00, 1200.00),
(7, 7, 1, 1500.00, 1500.00),
(8, 8, 2, 2000.00, 4000.00),
(9, 9, 5, 80.00, 400.00),
(10, 10, 3, 150.00, 450.00);

-- INSERT INTO items_customers
INSERT INTO items_customers (Item, Customer, Harga) VALUES
(1, 1, 250.00),
(2, 2, 500.00),
(3, 3, 750.00),
(4, 4, 1200.00),
(5, 5, 980.00),
(6, 6, 300.00),
(7, 7, 1500.00),
(8, 8, 2000.00),
(9, 9, 80.00),
(10, 10, 150.00);
