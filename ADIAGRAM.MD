Table users {
  id integer [primary key]
  name varchar
  username varchar
  is_admin boolean
  password varchar
  phone_number varchar
  created_at timestamp
}

Table settings{
  id integer [primary key]
  key varchar
  value varchar
}
// Ref: users.id < follows.following_user_id