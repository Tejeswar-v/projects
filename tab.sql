CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    blocked TINYINT(1) DEFAULT 0
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    count INT NOT NULL,
    cat VARCHAR(50) NOT NULL,
    description TEXT,
    image_url VARCHAR(500)
);
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    product_id INT NOT NULL,
    count INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

ALTER TABLE products ADD COLUMN is_deleted TINYINT(1) DEFAULT 0;

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Bajaj Frore 1200 mm', 1379, 6, 'home', 'Bajaj Frore 1200 mm (48") stars Rated Ceiling Fans for Home |BEE 1 star Rated Energy Efficient Ceiling Fan |Rust Free Coating for Long Life |High Air Delivery |2-Yr Warranty White', 'https://m.media-amazon.com/images/I/418-9+TCHUL._SX679_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('atomberg Efficio Alpha', 2799, 8, 'home', 'atomberg Efficio Alpha 1200mm BLDC Motor 5 Star Rated Classic Ceiling Fans with Remote Control | High Air Delivery Fan with LED Indicators | Upto 65% Energy Saving | 1+1 Year Warranty (Gloss Black)', 'https://m.media-amazon.com/images/I/519s6txKiQL._SX679_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Sonic BLDC FANS', 3719, 7, 'home', 'Sonic BLDC Fans offer an exceptional solution for those seeking energy-efficient cooling options. With a remarkable 60% energy savings compared to normal fans, they not only reduce electricity bills but also contribute to a greener environment. Beyond their energy-saving benefits, Sonic BLDC Fans boast a stunning design coupled with advanced technology. This combination not only enhances the aesthetics of any space but also ensures optimal performance and functionality.', 'https://www.venushomeappliances.com/storage/app/product/b3957280-ecfb-11ee-8f15-994b595d080e/20240328120708sonic-bldc-image-2.png');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Crompton SUREBREEZE SEA SAPPHIRA', 1449, 6, 'home', 'Crompton SUREBREEZE SEA SAPPHIRA 1200 mm (48 inch) Ceiling Fan (Lustre Brown) 1 Star', 'https://m.media-amazon.com/images/I/31zPa-3j81L._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Polycab Silencio Mini', 3198, 4, 'home', 'Polycab Silencio Mini 1200Mm Advanced Bldc 5 stars Rated Ceiling Fan With Remote|25 Speed Setting| (Matt Black)', 'https://m.media-amazon.com/images/I/51nsm-fbAOL._SX679_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Brrf Mini Thunder Handheld', 999, 5, 'home', 'Brrf Mini Thunder Handheld (upto 18 hours running) USB Rechargeable 4000 mAh Battery Operated Portable, Desk , Carry it Anywhere USB Fan (Blue)', 'https://rukminim2.flixcart.com/image/612/612/xif0q/usb-gadget/v/2/7/-original-imagzdhkufgev2nw.jpeg?q=70');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('LICHEE LPF-01 Mini Portable Fan', 899, 8, 'home', 'LICHEE LPF-01 USB Rechargeable Mini Portable Fan With LED Light Ultra High Speed Personal Fan USB Fan, Rechargeable Fan (Multicolor)', 'https://rukminim2.flixcart.com/image/612/612/xif0q/usb-gadget/s/d/1/usb-rechargeable-mini-portable-fan-with-led-light-ultra-high-original-imagzdg92veqh9uw.jpeg?q=70');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Rechargeable Neck Fan', 999, 8, 'home', 'Portable Rechargeable Neck Fan | Better Table Fans | USB Charging Battery Fan | Smart Mini Hand Free 3 High Speed Fan | 360° Cooling Low Noise Hanging Neck Fans for Sleeping Back Stomach (Green)', 'https://m.media-amazon.com/images/I/51onWLGF6OL._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Neck Fan', 500, 4, 'home', 'Neck Fan,Portable Neck Fans Rechargeable Personal Fans For Your Neck Wearable Fan Neck Air Conditioner Cooling Neck Fan With 360 Degree Rotation 2000mAh For Travel Sports Walking And Outdoor Working', 'https://m.media-amazon.com/images/I/714VjN4JONL._AC_UY327_FMwebp_QL65_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Gaiatop Mini Portable Fan', 799, 10, 'home', 'Gaiatop Mini Portable Fan, Powerful Hand Fan Table Fan Personal Small Desk Fan with Base, Cute Design 3 Speed Dual Motors Lightweight Handheld USB Rechargeable Fan for Women Men Indoor Outdoor (Black)', 'https://m.media-amazon.com/images/I/51K+SabkB5L._AC_CR0%2C0%2C0%2C0_SX615_SY462_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Samsung 1.5 Ton 5 Star', 44999, 10, 'home', 'Samsung 1.5 Ton 5 Star (5-in-1 Convertible Cooling, 2023 Model, AR18CYNZABE, Free Installation Worth Rs 1500), Stabilizer Free Operation, Inverter Split AC', 'https://m.media-amazon.com/images/I/51Ng7N+PcBL._SX679_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('LG 1.5 Ton 5 Star', 46000, 6, 'home', 'LG 1.5 Ton 5 Star DUAL Inverter Split AC (Copper, AI Convertible 6-in-1 Cooling, 4 Way, HD Filter with Anti-Virus Protection, 2024 Model, TS-Q19YNZE, White)', 'https://m.media-amazon.com/images/I/31DP9vR086L._SY445_SX342_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Haier 1.5 Ton 3 Star', 32800, 5, 'home', 'Haier 1.5 Ton 3 Star Twin Inverter Split AC (Copper, 5 in 1 Convertible, Anti Bacterial Filter, Cools at 54°C Temp, Long Air Throw - HSU17V-TMS3BN-INV,White,2024 Model)', 'https://m.media-amazon.com/images/I/51tTSPJbpsL._SX679_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Voltas 1.5 Ton 3 Star', 31000, 6, 'home', 'Voltas 1.5 Ton 3 Star, Inverter Split AC(Copper, 4-in-1 Adjustable Mode, Anti-dust Filter, 2023 Model, 183V Vectra Prism, White)', 'https://m.media-amazon.com/images/I/51K2hMHx06L._SL1500_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Panasonic 1.5 Ton', 44500, 6, 'home', 'Panasonic 1.5 Ton 5 Star Wi-Fi Inverter Smart Split AC (India\s 1st Matter Enabled RAC, Copper Condenser, 7in1 Convertible, True AI, 4 Way Swing, PM 0.1 Filter, CS/CU-NU18ZKY5W, 2024 Model, White)', 'https://m.media-amazon.com/images/I/51G96nyJlOL._SL1500_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Symphony Diet 12T Personal Tower Air Cooler', 5700, 5, 'home', 'Symphony Diet 12T Personal Tower Air Cooler for Home with Honeycomb Pad, Powerful Blower, i-Pure Technology and Low Power Consumption (12L, White)', 'https://m.media-amazon.com/images/I/51SKchiKLQL._SX679_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Crompton Ozone Royale', 9400, 6, 'home', 'Crompton Ozone Royale 75 Litres Desert Air Cooler for home | Large & Easy Clean Ice Chamber | High Density Honeycomb Pads | Everlast Pump | Humidity...', 'https://m.media-amazon.com/images/I/51JGCCAL+FL._SL1100_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('EECOCOOL Bulbul', 7690, 5, 'home', 'EECOCOOL Bulbul 70 L Desert Air Cooler for Home, Large Honeycomb Cooling Pad with Auto Swing Technology | Inverter Compatible, Powerful Airflow Cooler for…', 'https://m.media-amazon.com/images/I/51fFPjbsRrL._SL1080_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url) 
VALUES ('Hindware Desert Air Cooler', 11000, 5, 'home', 'Hindware Smart Appliances Spectra 80L Desert Air Cooler with Honeycomb Pads, Inverter Compatible, Castor wheels and motorized air flow control & High.', 'https://m.media-amazon.com/images/I/71Lmww4EeHL._SL1500_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('HP Victus Gaming Laptop', 78990, 10, 'laptop', 'HP Victus Gaming Laptop, 12th Gen Intel Core i7-12650H, 4GB RTX 3050 GPU, 15.6-inch (39.6 cm), 75W TGP, FHD, IPS, 144Hz, 16GB DDR4, 512GB SSD, Backlit KB, B&O (MSO, Blue, 2.37 kg), fa0188TX', 'https://m.media-amazon.com/images/I/71foAS+2AjL._SL1500_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('ASUS TUF Gaming F15', 57990, 10, 'laptop', 'ASUS TUF Gaming F15, 15.6"(39.62 cms) FHD 144Hz, Intel Core i5-11400H 11th Gen, RTX 3050 4GB Graphics, Gaming Laptop (8GB/512GB SSD/90WHrs Battery/Windows 11/Black/2.3 kg), FX506HC-HN089W', 'https://m.media-amazon.com/images/I/91zVSkGGZbS._SY450_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Lenovo ThinkBook 15', 57990, 10, 'laptop', 'Lenovo ThinkBook 15 Intel 12th Gen Core i7 15.6" (39.62cm) FHD IPS 300 Nits Antiglare Thin and Light Laptop (16GB/512GB SSD/Windows 11 Home/Backlit/Mineral Grey/1Y Premier Support/1.7 Kg), 21DJA0Y0IN', 'https://m.media-amazon.com/images/I/51UoiQ-zvEL._SY450_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Dell 14 Laptop', 34990, 5, 'laptop', 'Dell 14 Laptop, 12th Gen Intel Core i3-1215U Processor/8GB/512GB SSD/Intel UHD Graphics/14.0"(35.56cm) FHD/Windows 11 + MSO''21/15 Month McAfee/Spill-Resistant Keyboard/Grey/Thin & Light 1.48kg', 'https://m.media-amazon.com/images/I/412qecfhY9L._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Apple 2023 MacBook Pro', 399900, 8, 'laptop', 'Apple 2023 MacBook Pro (16-inch, M3 Max chip with 16‑core CPU and 40‑core GPU, 48GB Unified Memory, 1TB) - Space Black', 'https://m.media-amazon.com/images/I/618d5bS2lUL._SX425_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Acer Aspire Lite', 46990, 9, 'laptop', 'Acer Aspire Lite 12th Gen Intel Core i5-1235U Thin and Light Metal Laptop (Windows 11 Home/8GB RAM/512GB SSD/Intel Iris Xe Graphics/MSO) AL15-52, 39.62cm (15.6") Full HD Display, Steel Gray, 1.59 KG', 'https://m.media-amazon.com/images/I/51KL3aOZ0tL._SY450_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('realme NARZO 70 Pro', 19999, 15, 'mobile', 'realme NARZO 70 Pro 5G (Glass Green, 8GB RAM,128GB Storage) Dimensity 7050 5G Chipset | Horizon Glass Design | Segment 1st Flagship Sony IMX890 OIS Camera', 'https://m.media-amazon.com/images/I/41nRYrr3FGL._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Redmi 13C 5G', 11999, 10, 'mobile', 'Redmi 13C 5G (Startrail Green,6GB RAM, 128GB Storage) | MediaTek Dimensity 6100+ 5G | 90Hz Display', 'https://m.media-amazon.com/images/I/41OtlIrnUbL._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Samsung Galaxy M55 5G', 29999, 15, 'mobile', 'Samsung Galaxy M55 5G (Light Green,8GB RAM,256GB Storage) | 50MP Triple Cam| 5000mAh Battery| Snapdragon 7 Gen 1 | 4 Gen. OS Upgrade & 5 Year Security Update| Super AMOLED+ Display| Without Charger', 'https://m.media-amazon.com/images/I/41bbNrwkZUL._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Oneplus Nord CE4', 26999, 10, 'mobile', 'Oneplus Nord CE4 (Dark Chrome, 8GB RAM, 256GB Storage)', 'https://m.media-amazon.com/images/I/417odtnpERL._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('realme narzo 60X 5G', 11499, 5, 'mobile', 'realme narzo 60X 5G (Stellar Green,6GB,128GB Storage) Up to 2TB External Memory | 50 MP AI Primary Camera | Segments only 33W Supervooc Charge', 'https://m.media-amazon.com/images/I/81WimZLWH1L._SX466_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Apple iPhone 15 Pro', 177900, 5, 'mobile', 'Apple iPhone 15 Pro (1 TB) - Natural Titanium', 'https://m.media-amazon.com/images/I/412CKVTe8dL._SY445_SX342_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Motorola Edge 40 Neo', 27100, 5, 'mobile', 'Motorola Edge 40 Neo (Black Beauty, 256 GB) (12 GB RAM)', 'https://m.media-amazon.com/images/I/61+YjjTq+nL._SY879_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Lemorele 5 in 1 USB C Hub HDMI Multiport Adapter USB C Dock', 1439, 15, 'accessory', 'Lemorele 5 in 1 USB C Hub HDMI Multiport Adapter USB C Dock with 4K HDMI, 3*USB A, 100W PD Compatible with iPad Pro, MacBook Pro/Air M1, Switch, PS4, Xbox etc.', 'https://m.media-amazon.com/images/I/61GDK3pSaUL._SY550_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('Portronics My Buddy Air Cooling Pad Laptop Stand', 448, 10, 'accessory', 'Portronics My Buddy Air Cooling Pad Laptop Stand with 6 Cooling Fans, RGB Lights, 7 Adjustable Heights, Mobile Stand for Upto 17 Inches Laptop (Black)', 'https://m.media-amazon.com/images/I/61VhrjxYN6L._SY450_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('MI Power Bank 3i', 1330, 15, 'accessory', 'MI Power Bank 3i 20000mAh Lithium Polymer 18W Fast Power Delivery Charging | Input- Type C | Micro USB| Triple Output | Black.', 'https://m.media-amazon.com/images/I/31grUs8OpvL._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('pTron Bassbuds', 899, 10, 'accessory', 'pTron Newly Launched Bassbuds Gomax TWS Earbuds, TruTalk AI-ENC Calls, 36Hrs Playtime, 13mm Drivers, in-Ear Bluetooth 5.3 Wireless Headphones, Voice Assistant, Type-C Fast Charging & IPX5 (Black)', 'https://m.media-amazon.com/images/I/315WwA-R6kL._SX300_SY300_QL70_FMwebp_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('pTron Bullet Pro 36W PD Quick Charger', 350, 15, 'accessory', 'pTron Bullet Pro 36W PD Quick Charger, 3 Port Fast Car Charger Adapter - Compatible with All Smartphones & Tablets (Black)', 'https://m.media-amazon.com/images/I/61aBH+cXL2L._AC_UL320_.jpg');

INSERT INTO products (name, price, count, cat, description, image_url)
VALUES ('STRIFF Multi Angle Tablet/Mobile Stand', 100, 10, 'accessory', 'STRIFF Multi Angle Tablet/Mobile Stand. Phone Holder for iPhone, Android, Samsung, OnePlus, Xiaomi. Portable,Foldable Cell Phone Stand.Perfect for Bed,Office, Home,Gift and Desktop (Black)', 'https://m.media-amazon.com/images/I/31iE517+NFL._SY300_SX300_.jpg');