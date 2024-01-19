<?php
$category = FranchiseCategory::Data(['status'=>['publish']])->pluck('category_name', 'id');