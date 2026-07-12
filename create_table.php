<?php
require_once __DIR__ . '/vendor/autoload.php';
include "admin/model/pdo.php";
$sql = "CREATE TABLE IF NOT EXISTS coupons (
  id_coupon int(11) NOT NULL AUTO_INCREMENT,
  code varchar(50) NOT NULL,
  discount_type tinyint(1) NOT NULL DEFAULT '1',
  discount_value int(11) NOT NULL,
  max_discount int(11) NOT NULL DEFAULT '0',
  min_order_value int(11) NOT NULL DEFAULT '0',
  product_id int(11) NOT NULL DEFAULT '0',
  start_date datetime NOT NULL,
  end_date datetime NOT NULL,
  usage_limit int(11) NOT NULL DEFAULT '0',
  used_count int(11) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id_coupon),
  UNIQUE KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn = pdo_get_connection();
$conn->exec($sql);

$conn->exec("ALTER TABLE bill
  ADD COLUMN IF NOT EXISTS coupon_code VARCHAR(50) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS discount_amount INT(11) NOT NULL DEFAULT 0");

echo "Table created";
