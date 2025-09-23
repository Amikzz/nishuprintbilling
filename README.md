# Nisu Print System

**Nisu Print System** is a streamlined solution for managing invoice and delivery note creation for businesses. It allows users to generate, track, and manage invoices and delivery notes efficiently, providing PDF outputs, storage, and download functionality. Built with **Laravel**, it handles purchase orders, customer details, and itemized billing automatically.

---

## Features

- **Invoice Creation & Management**
  - Generate invoices linked to purchase orders.
  - Automatically calculate totals and currency conversion using exchange rates.
  - Maintain invoice status updates: *Order Dispatched*, *Order Complete*.
  - PDF generation and storage for all invoices.
  
- **Delivery Note Creation**
  - Create delivery notes based on purchase orders and invoice numbers.
  - Paginated item lists for organized PDF outputs.
  - Delivery note number management and storage.
  
- **Customer & Purchase Order Integration**
  - Fetch customer and purchase order details automatically.
  - Map purchased items to detailed records including item code, size, color, UPC, and unit price.
  
- **File Management**
  - Store generated PDFs in the public disk.
  - Download invoices and delivery notes directly from storage.
  
- **Master Sheet Updates**
  - Synchronize invoice and delivery note information with the master sheet.
  - Track status and timestamps for delivery and billing.

---

## Technology Stack

- **Backend:** PHP, Laravel 11
- **PDF Generation:** [Barryvdh/DomPDF](https://github.com/barryvdh/laravel-dompdf)
- **Database:** MySQL / MariaDB
- **Storage:** Laravel’s Filesystem (local/public)
- **Logging:** Laravel Log for tracking actions and errors

---

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/nisu-print-system.git
   cd nisu-print-system
   ```

2. Install Dependencies
   ```bash
   composer install
   ```

3. Copy .env file and configure your database and storage:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations
    ```bash
    php artisan migrate
    ```

5. Serve the application
   ```bash
    php artisan serve
   ```

MIT License

Copyright (c) 2025 [Amika Subasinghe. Rangiri Holdings]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES, OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
