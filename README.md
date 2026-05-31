To run and interact with the newly developed ERP system locally, follow these simple steps:

### 1. Start XAMPP Services
Make sure your local servers are active.
1. Open the **XAMPP Control Panel** on your Windows system.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

---

### 2. Access the Portal in Your Browser
Open your preferred web browser and navigate to the following local address:
```text
http://localhost/erpSystem/
```
*(Because we implemented a transparent root `.htaccess` redirect, this will automatically forward you to the secure login page at `http://localhost/erpSystem/login`).*

---

### 3. Log In with Seeded Credentials
Use any of the following pre-configured enterprise profiles to log in:

| Role Profile | Username | Password |
| :--- | :--- | :--- |
| **Super Admin** | `admin` | `admin123` |
| **HR Manager** | `hr_manager` | `admin123` |
| **Finance Manager** | `finance_manager` | `admin123` |
| **Sales Manager** | `sales_manager` | `admin123` |
| **Inventory Manager** | `inventory_manager` | `admin123` |

---

### 4. Features to Explore Immediately
Once logged in, you will be taken to the **Corporate Command Desk**:
* **Theme Toggle**: Click the Sun/Moon icon in the top right header control panel to dynamically switch between Light and Dark mode themes (persists on reload!).
* **Live Audit Trails**: Look at the *Corporate Security Feed & Audit Logs* table at the bottom. It dynamically lists every login action, including timestamps, actor details, action types, and IP addresses fetched straight from your MySQL database.
* **Role-Based Sidebars**: If you log in as the `hr_manager` or `inventory_manager` instead of `admin`, notice how the sidebar options dynamically customize to match only the permissions granted to that specific manager role.
