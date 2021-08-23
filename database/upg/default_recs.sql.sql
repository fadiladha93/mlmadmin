insert into public.users (firstname, lastname, email, password, usertype)
values('admin', 'user', 'admin@gmail.com', '$2y$10$SUPX6nU0gxQozOzqx9TFT.SEmIRh0qtHS1voJN5mV2WNK6tAd9Hgy', 1)

update public.users
set password = '$2y$10$DRJrS1UBeLrSvLGurHtefemFCGQlY8rwF6O3Jsa3BFncXFFKZ4QkO'
where usertype = 1;
