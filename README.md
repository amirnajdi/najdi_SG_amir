# Shopping list
This project is a simple shopping list, Create By php v8.1

### Run Project
1. Clone the project on your os
2. Run ```bash composer install```
3. Import ```doc/shopping_list.sql``` on your MySQL
4. Create ```.env``` file base on ```.env.example`` file and edit base on your setting
5. Run ```bash php -S localhost:8090 -t src/Public/ ``` 
### Services
1. **Get All Items** (GET): ```{{baseUrl}}/items```
2. **Find Item** (GET): ```{{baseUrl}}/items/:uuid```
3. **Create Item** (POST): ```{{baseUrl}}/items``` | required: title
4. **Update Item** (PUT): ```{{baseUrl}}/items/:uuid``` | required: title
5. **Change the status of the item to done or not done** (PUT): ```{{baseUrl}}/items/:uuid/status/toggle```
6. **Delete Item** (DELETE): ```{{baseUrl}}/items/:uuid```

**note**: you can import ```doc/najdi_SG_amir.postman_collection.json``` file to postman and use services
