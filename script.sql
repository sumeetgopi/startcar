create database sukhdeep;
use sukhdeep;

create table category (
  id int(11) primary key auto_increment,
  parent_id int(11) default 0,
  category_number varchar(255) default '',
  category_name varchar(255) default '',
  category_image varchar(255) default '',
  status tinyint(4) default 0,

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

create table unit (
  id int(11) primary key auto_increment,
  unit_number varchar(255) default '',
  unit_name varchar(255) default null,
  status tinyint(4) default 0,

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

create table product (
  id int(11) primary key auto_increment,
  category_id int(11),
  sub_category_id int(11),
  unit_id int(11),

  product_number varchar(255) default '',
  product_name varchar(255) default '',
  weight decimal(10, 2) default 0,
  description varchar(1000) default '',
  mrp_price decimal(10, 2) default 0,
  our_price decimal(10, 2) default 0,
  status tinyint(4) default 0,

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

create table product_image(
  id int(11) primary key auto_increment,
  product_id int(11),
  product_image varchar(255) default '',

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

create table orders (
  id int(11) primary key auto_increment,
  customer_id int(11),

  order_number varchar(255) default '',
  order_status enum('pending','accepted','delivery','completed','canceled') default 'pending',

  order_date datetime default null,
  accepted_date datetime default null,
  processing_date datetime default null,
  shipped_date datetime default null,
  completed_date datetime default null,
  canceled_date datetime default null,

  total_amount decimal(10, 2) default 0,
  total_product int(11) default 0,
  payment_mode enum('cash', 'online'),
  transaction_id varchar(255) default '',

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

create table order_detail (
  id int(11) primary key auto_increment,
  order_id int(11),
  customer_id int(11),
  product_id int(11),
  category_id int(11),
  sub_category_id int(11),
  unit_id int(11),

  product_price decimal(10, 2) default 0,
  quantity int(11) default 0,
  amount decimal(10, 2) default 0,

  order_detail_status enum('pending','accepted','delivery','completed','canceled') default 'pending',

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

ALTER TABLE `orders` CHANGE `order_status` `order_status` ENUM('pending','accepted','delivery','completed','canceled') DEFAULT 'pending';
ALTER TABLE `order_detail` CHANGE `order_detail_status` `order_detail_status` ENUM('pending','accepted','delivery','completed','canceled') DEFAULT 'pending';