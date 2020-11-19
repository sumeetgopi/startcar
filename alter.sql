alter table users add column otp_code varchar(10) default '';
alter table users add column device_type enum('android', 'ios') default 'android';

alter table users drop index users_email_unique;

alter table category add column description text after category_name;

create table cart (
  id int(11) primary key auto_increment,
  customer_id int(11),
  product_id int(11),
  quantity int(11) default 0
);

create table user_address(
  id int(11) primary key auto_increment,
  customer_id int(11),
  address text,
  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

create table coupon (
  id int(11) primary key auto_increment,
  coupon_type enum('cashback', 'coupon'),
  sr_number varchar(255) default '',
  name varchar(255) default '',
  code varchar(100) default '',
  description varchar(255) default '',
  status tinyint(4) default 0,
  expiry_date date default null,
  apply_type enum('single', 'multiple'),
  cb_amount decimal(10, 2) default 0 comment 'cashback amount',

  c_discount_type enum('fixed', 'percent') default 'fixed' comment 'coupon field',

  c_order_amount_upto decimal(10, 2) default 0 comment 'coupon field',
  c_order_amount_upto_fix_amount decimal(10, 2) default 0 comment 'coupon field, if discount is fixed',
  c_order_amount_upto_percent decimal(10, 2) default 0 comment 'coupon field, if discount is percent',

  c_order_amount_more_than decimal(10, 2) default 0 comment 'coupon field',
  c_order_amount_more_than_fix_amount decimal(10, 2) default 0 comment 'coupon field',

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

alter table orders add column coupon_id int(11) default 0 after customer_id;
alter table orders add column user_address_id int(11) default 0 after customer_id;
alter table orders add column grand_amount decimal(10, 2) default 0 after canceled_date; 
alter table orders add column coupon_discount_amount decimal(10, 2) default 0 after total_amount; 
alter table users add column cashback_amount decimal(10, 2) default 0 after password;

create table user_coupon_used (
  id int(11) primary key auto_increment,
  customer_id int(11),
  order_id int(11),
  coupon_id int(11), 

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

alter table orders add column cashback_offer_amount decimal(10, 2) default 0 after coupon_discount_amount; 
alter table orders add column cashback_redeem_amount decimal(10, 2) default 0 after coupon_discount_amount; 

create table setting 
(
  id int(11) primary key auto_increment,
  center_image varchar(255) default '',
  policy text,
  about_us text,
  term_condition text,

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

alter table setting add column maximum_cashback_amount decimal(10, 2) default 0 after term_condition;
alter table setting add column minimum_order_amount_for_cashback decimal(10, 2) default 0 after maximum_cashback_amount;

create table banner 
(
  id int(11) primary key auto_increment,
  image varchar(255) default '',
  status tinyint(4) default 0,

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);


create table sms_template
(
  id int(11) primary key auto_increment,
  sms_name varchar(255) default '',
  template text,
  status TINYINT(4) default 0,

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);


alter table users add column status tinyint(4) default 0 after email;
update users set status = 1;

alter table users add column fcm_token varchar(255) default '' after email;

alter table setting add column customer_support varchar(255) default '' after term_condition;
alter table setting add column read_policy varchar(255) default '' after term_condition;

create table sms (
  id int(11) primary key auto_increment,
  message text,
  mobile_number text,

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

create table notification (
  id int(11) primary key auto_increment,
  title text,
  message text,
  mobile_number text,

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

delete from users where fcm_token = '' and id != '1';
delete from users where status = '0' and id != '1';


alter table setting add column invoice_address text after minimum_order_amount_for_cashback;


alter table orders add column customer_cancel_reason varchar(255) default '' after order_status;
alter table orders add column admin_cancel_reason varchar(255) default '' after order_status;

create table tag
(
  id int(11) primary key auto_increment,
  tag_name varchar(255) default '',
  
  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

create table product_tag
(
  id int(11) primary key auto_increment,
  product_id int(11),
  tag_id int(11),

  created_at timestamp null default null,
  updated_at timestamp null default null,
  deleted_at timestamp null default null
);

alter table users add column rozarpay_customer_id varchar(255) default '' after id;
alter table orders add column razorpay_order_id varchar(255) default '' after id;