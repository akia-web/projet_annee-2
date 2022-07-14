<?php

namespace App\Service;

use App\Repository\CategoriesRepository;


class CategorieService{
    public function getAllCategories(CategoriesRepository $categoriesRepository){
        $findAllCategory = $categoriesRepository->findAllCategories();
        return $findAllCategory;

    }
}