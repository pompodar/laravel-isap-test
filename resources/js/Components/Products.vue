<template>
    <div>

        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
            <select id="category" v-model="selectedCategory" @change="filterProducts" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">All</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="brand" class="block text-sm font-medium text-gray-700">Brand:</label>
            <input type="text" id="brand" v-model="selectedBrand" @input="filterProducts" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="mb-4">
            <label for="size" class="block text-sm font-medium text-gray-700">Size:</label>
            <input type="text" id="size" v-model="selectedSize" @input="filterProducts" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">SKU</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Brand</th>
                    <th class="px-4 py-2">Size</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in filteredProducts" :key="product.id">
                    <td class="border px-4 py-2">
                        <input type="text" v-model="product.name" @blur="updateProduct(product)">
                    </td>
                    <td class="border px-4 py-2">
                        <input type="text" v-model="product.sku" @blur="updateProduct(product)">
                    </td>
                    <td class="border px-4 py-2">
                        <p>{{product.category.name}}</p>
                    </td>
                    <td class="border px-4 py-2">
                        <input type="number" v-model="product.price" @blur="updateProduct(product)">
                    </td>
                    <td class="border px-4 py-2">
                        <input type="text" v-model="product.description" @blur="updateProduct(product)">
                    </td>
                    <td class="border px-4 py-2">
                        <input type="text" v-model="product.brand" @blur="updateProduct(product)">
                    </td>
                    <td class="border px-4 py-2">
                        <input type="text" v-model="product.size" @blur="updateProduct(product)">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted, watchEffect } from 'vue';
import axios from 'axios';

const products = ref([]);
const categories = ref([]);
const selectedCategory = ref('');
const filteredProducts = ref([]);
const selectedBrand = ref('');
const selectedSize = ref('');

const fetchProducts = async () => {
    try {
        const response = await axios.get('/products');
        products.value = response.data;
    } catch (error) {
        console.error('Error fetching products:', error);
    }
};

const fetchCategories = async () => {
    try {
        const response = await axios.get('/categories');
        categories.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const updateProduct = async (updatedProduct) => {
    try {
        const response = await axios.put(`/products/${updatedProduct.id}`, updatedProduct);
        console.log(response.data); // Handle response as needed
    } catch (error) {
        console.error('Error updating product:', error);
    }
};

const filterProducts = () => {
    filteredProducts.value = products.value.filter(product => {
        // Filter by category
        if (selectedCategory.value && product.category_id != selectedCategory.value) {
            return false;
        }

        // Filter by brand
        if (selectedBrand.value && !product.brand.toLowerCase().includes(selectedBrand.value.toLowerCase())) {
            return false;
        }

        // Filter by size
        if (selectedSize.value && !product.size.toLowerCase().includes(selectedSize.value.toLowerCase())) {
            return false;
        }

        return true;
    });
};

onMounted(
  fetchProducts(),
  fetchCategories()
);

watchEffect(() => {
    filterProducts();
});

</script>
