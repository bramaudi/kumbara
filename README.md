# nulis
Is a headless blog, provide REST API cms.

## API Endpoint

### Catgeory

* **Create**
* POST: `/api.category/create`
* Data: `name`, (`slug`)

---

* **Read**
* GET: `/api.category`
* GET: `/api.category/{id|slug}`

---

* **Update**
* POST: `/api.category/update`
* Data: `id`, `name`, (`slug`)

---

* **Delete**
* POST: `/api.category/delete`
* Data: `slug`