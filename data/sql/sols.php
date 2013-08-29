<?php

header("Content-Type: text/plain");

// -- 1 sol
for($i = 52; $i <= 5841; $i++){
	printf('INSERT INTO ticket (bubble_tag, amount, status, expire_at, created_at, updated_at) VALUES ("D60N00A%06d", 1.00, 1, "2011-12-31", now(), now());'."\n", $i);
}

for($i = 12158; $i <= 17948; $i++){
	printf('INSERT INTO ticket (bubble_tag, amount, status, expire_at, created_at, updated_at) VALUES ("D60N00A%06d", 1.00, 1, "2011-12-31", now(), now());'."\n", $i);
}

for($i = 6227; $i <= 12153  ; $i++){
	printf('INSERT INTO ticket (bubble_tag, amount, status, expire_at, created_at, updated_at) VALUES ("D60N00A%06d", 1.00, 1, "2011-12-31", now(), now());'."\n", $i);
}
for($i = 18026; $i <= 19992  ; $i++){
	printf('INSERT INTO ticket (bubble_tag, amount, status, expire_at, created_at, updated_at) VALUES ("D60N00A%06d", 1.00, 1, "2011-12-31", now(), now());'."\n", $i);
}

// -- 5 sols
for($i = 2; $i <= 1499   ; $i++){
	printf('INSERT INTO ticket (bubble_tag, amount, status, expire_at, created_at, updated_at) VALUES ("D60N00B%06d", 5.00, 1, "2011-12-31", now(), now());'."\n", $i);
}
// -- 10 sols
for($i = 2; $i <= 999   ; $i++){
	printf('INSERT INTO ticket (bubble_tag, amount, status, expire_at, created_at, updated_at) VALUES ("D60N00C%06d", 10.00, 1, "2011-12-31", now(), now());'."\n", $i);
}


?>