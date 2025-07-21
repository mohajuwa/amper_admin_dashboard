import os
import textwrap

# ANSI escape codes for colors
class colors:
    HEADER = '\033[95m'
    OKBLUE = '\033[94m'
    OKCYAN = '\033[96m'
    OKGREEN = '\033[92m'
    WARNING = '\033[93m'
    FAIL = '\033[91m'
    ENDC = '\033[0m'
    BOLD = '\033[1m'
    UNDERLINE = '\033[4m'

# --- Configuration ---
# Define the table names and their corresponding model names
TABLE_MODEL_MAP = {
    "order_offers": "OrderOffer",
    "vendor_responses": "VendorResponse",
    "customer_responses": "CustomerResponse",
    "order_activity_log": "OrderActivityLog",
    "order_negotiations": "OrderNegotiation",
}

# --- SQL Schemas ---
SQL_SCHEMAS = {
    "order_offers": """
        CREATE TABLE `order_offers` (
          `offer_id` INT(11) NOT NULL AUTO_INCREMENT,
          `order_id` INT(11) NOT NULL,
          `vendor_id` INT(11) NOT NULL,
          `admin_id` INT(11) NOT NULL,
          `offer_type` ENUM('service_quote', 'negotiation', 'counter_offer') DEFAULT 'service_quote',
          `offered_price` DECIMAL(10,2) NOT NULL,
          `service_details` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`service_details`)),
          `offer_notes` TEXT DEFAULT NULL,
          `expires_at` DATETIME DEFAULT NULL,
          `status` ENUM('pending', 'accepted', 'rejected', 'expired', 'cancelled') DEFAULT 'pending',
          `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
          PRIMARY KEY (`offer_id`),
          KEY `idx_order_vendor` (`order_id`, `vendor_id`),
          KEY `idx_status_created` (`status`, `created_at`),
          CONSTRAINT `order_offers_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
          CONSTRAINT `order_offers_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE,
          CONSTRAINT `order_offers_admin_fk` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
    """,
    "vendor_responses": """
        CREATE TABLE `vendor_responses` (
          `response_id` INT(11) NOT NULL AUTO_INCREMENT,
          `offer_id` INT(11) NOT NULL,
          `vendor_id` INT(11) NOT NULL,
          `response_type` ENUM('accept', 'reject', 'counter_offer', 'request_modification') DEFAULT 'accept',
          `response_price` DECIMAL(10,2) DEFAULT NULL,
          `response_notes` TEXT DEFAULT NULL,
          `rejection_reason` VARCHAR(255) DEFAULT NULL,
          `attachments` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
          `responded_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`response_id`),
          KEY `idx_offer_vendor` (`offer_id`, `vendor_id`),
          CONSTRAINT `vendor_responses_offer_fk` FOREIGN KEY (`offer_id`) REFERENCES `order_offers` (`offer_id`) ON DELETE CASCADE,
          CONSTRAINT `vendor_responses_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
    """,
    "customer_responses": """
        CREATE TABLE `customer_responses` (
          `response_id` INT(11) NOT NULL AUTO_INCREMENT,
          `order_id` INT(11) NOT NULL,
          `offer_id` INT(11) DEFAULT NULL,
          `user_id` INT(11) NOT NULL,
          `response_type` ENUM('accept', 'reject', 'request_modification', 'cancel_order') DEFAULT 'accept',
          `final_agreed_price` DECIMAL(10,2) DEFAULT NULL,
          `customer_notes` TEXT DEFAULT NULL,
          `preferred_schedule` DATETIME DEFAULT NULL,
          `responded_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`response_id`),
          KEY `idx_order_user` (`order_id`, `user_id`),
          CONSTRAINT `customer_responses_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
          CONSTRAINT `customer_responses_offer_fk` FOREIGN KEY (`offer_id`) REFERENCES `order_offers` (`offer_id`) ON DELETE SET NULL,
          CONSTRAINT `customer_responses_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
    """,
    "order_activity_log": """
        CREATE TABLE `order_activity_log` (
          `activity_id` INT(11) NOT NULL AUTO_INCREMENT,
          `order_id` INT(11) NOT NULL,
          `actor_type` ENUM('admin', 'vendor', 'customer', 'system') NOT NULL,
          `actor_id` INT(11) NOT NULL,
          `activity_type` ENUM(
            'offer_sent', 'offer_accepted', 'offer_rejected', 'counter_offer_made',
            'price_negotiated', 'vendor_assigned', 'order_confirmed', 'order_cancelled',
            'payment_processed', 'service_started', 'service_completed'
          ) NOT NULL,
          `activity_description` TEXT NOT NULL,
          `related_offer_id` INT(11) DEFAULT NULL,
          `related_response_id` INT(11) DEFAULT NULL,
          `previous_status` VARCHAR(50) DEFAULT NULL,
          `new_status` VARCHAR(50) DEFAULT NULL,
          `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
          `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`activity_id`),
          KEY `idx_order_activity` (`order_id`, `activity_type`, `created_at`),
          CONSTRAINT `order_activity_log_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
    """,
    "order_negotiations": """
        CREATE TABLE `order_negotiations` (
          `negotiation_id` INT(11) NOT NULL AUTO_INCREMENT,
          `order_id` INT(11) NOT NULL,
          `vendor_id` INT(11) NOT NULL,
          `negotiation_round` INT(11) NOT NULL DEFAULT 1,
          `initiator_type` ENUM('admin', 'vendor') NOT NULL,
          `initiator_id` INT(11) NOT NULL,
          `proposed_price` DECIMAL(10,2) NOT NULL,
          `negotiation_notes` TEXT DEFAULT NULL,
          `status` ENUM('active', 'accepted', 'rejected', 'superseded') DEFAULT 'active',
          `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`negotiation_id`),
          KEY `idx_order_vendor_round` (`order_id`, `vendor_id`, `negotiation_round`),
          CONSTRAINT `order_negotiations_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
          CONSTRAINT `order_negotiations_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
    """
}

# --- Model Templates ---
MODEL_TEMPLATES = {
    "OrderOffer": """
        <?php

        namespace App\\Models;

        use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
        use Illuminate\\Database\\Eloquent\\Model;

        class OrderOffer extends Model
        {
            use HasFactory;
            protected $primaryKey = 'offer_id';
            protected $guarded = [];

            public function order() {
                return $this->belongsTo(OrderModel::class, 'order_id', 'order_id');
            }

            public function vendor() {
                return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
            }

            public function admin() {
                return $this->belongsTo(Admin::class, 'admin_id', 'id');
            }

            public function vendorResponses() {
                return $this->hasMany(VendorResponse::class, 'offer_id', 'offer_id');
            }
        }
    """,
    "VendorResponse": """
        <?php

        namespace App\\Models;

        use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
        use Illuminate\\Database\\Eloquent\\Model;

        class VendorResponse extends Model
        {
            use HasFactory;
            protected $primaryKey = 'response_id';
            protected $guarded = [];

            public function offer() {
                return $this->belongsTo(OrderOffer::class, 'offer_id', 'offer_id');
            }

            public function vendor() {
                return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
            }
        }
    """,
    "CustomerResponse": """
        <?php

        namespace App\\Models;

        use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
        use Illuminate\\Database\\Eloquent\\Model;

        class CustomerResponse extends Model
        {
            use HasFactory;
            protected $primaryKey = 'response_id';
            protected $guarded = [];

            public function order() {
                return $this->belongsTo(OrderModel::class, 'order_id', 'order_id');
            }

            public function offer() {
                return $this->belongsTo(OrderOffer::class, 'offer_id', 'offer_id');
            }

            public function user() {
                return $this->belongsTo(User::class, 'user_id', 'user_id');
            }
        }
    """,
    "OrderActivityLog": """
        <?php

        namespace App\\Models;

        use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
        use Illuminate\\Database\\Eloquent\\Model;

        class OrderActivityLog extends Model
        {
            use HasFactory;
            protected $primaryKey = 'activity_id';
            protected $guarded = [];
            const UPDATED_AT = null; // Activity logs are immutable

            public function order() {
                return $this->belongsTo(OrderModel::class, 'order_id', 'order_id');
            }
        }
    """,
    "OrderNegotiation": """
        <?php

        namespace App\\Models;

        use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
        use Illuminate\\Database\\Eloquent\\Model;

        class OrderNegotiation extends Model
        {
            use HasFactory;
            protected $primaryKey = 'negotiation_id';
            protected $guarded = [];

            public function order() {
                return $this->belongsTo(OrderModel::class, 'order_id', 'order_id');
            }

            public function vendor() {
                return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
            }
        }
    """
}

# --- Helper Functions ---
def run_command(command):
    """Executes a shell command and prints its output."""
    print(f"{colors.OKCYAN}Executing: {command}{colors.ENDC}")
    os.system(command)

def create_file(path, content):
    """Creates a file with the given content, creating directories if needed."""
    try:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        with open(path, 'w') as f:
            f.write(textwrap.dedent(content.strip()))
        print(f"{colors.OKGREEN}Successfully created file: {path}{colors.ENDC}")
    except Exception as e:
        print(f"{colors.FAIL}Error creating file {path}: {e}{colors.ENDC}")

def generate_migrations():
    """Generates and populates the migration files."""
    print(f"\n{colors.HEADER}--- Step 1: Generating Migration Files ---{colors.ENDC}")
    for table_name in TABLE_MODEL_MAP.keys():
        # Use a placeholder timestamp to ensure unique migration filenames
        from datetime import datetime
        timestamp = datetime.now().strftime('%Y_%m_%d_%H%M%S')
        # A small delay to ensure the next file has a different timestamp
        import time
        time.sleep(1)

        migration_name = f"create_{table_name}_table"
        file_path = f"database/migrations/{timestamp}_{migration_name}.php"

        migration_content = f"""
            <?php

            use Illuminate\\Database\\Migrations\\Migration;
            use Illuminate\\Database\\Schema\\Blueprint;
            use Illuminate\\Support\\Facades\\Schema;
            use Illuminate\\Support\\Facades\\DB;

            return new class extends Migration
            {{
                public function up(): void
                {{
                    DB::unprepared("
                    {SQL_SCHEMAS[table_name].strip()}
                    ");
                }}

                public function down(): void
                {{
                    Schema::dropIfExists('{table_name}');
                }}
            }};
        """
        create_file(file_path, migration_content)

def generate_models():
    """Generates the Eloquent model files."""
    print(f"\n{colors.HEADER}--- Step 2: Generating Eloquent Models ---{colors.ENDC}")
    model_path_dir = "app/Models"
    if not os.path.exists(model_path_dir):
        os.makedirs(model_path_dir)

    for model_name, template in MODEL_TEMPLATES.items():
        file_path = f"{model_path_dir}/{model_name}.php"
        create_file(file_path, template)

def display_next_steps():
    """Displays instructions for the user."""
    controller_logic = """
    // In your OrderController.php, update the method that shows the order details:
    public function show($order_id)
    {
        // Eager load the new relationships to avoid N+1 query problems
        $getRecord = Order::with([
            'offers.vendor',          // Get offers and the associated vendor
            'offers.vendorResponses', // Get the responses for each offer
            'activityLog',            // Get the full activity log
            'negotiations.vendor'     // Get negotiation history
        ])->findOrFail($order_id);

        $vendor = Vendor::all(); // You already have this

        // The rest of your existing data fetching logic...
        // $getScheduling = ...

        return view('admin.order.details', compact('getRecord', 'vendor', /* other variables */));
    }
    """

    order_model_update = """
    // In app/Models/Order.php, add these relationships:
    public function offers()
    {
        return $this->hasMany(OrderOffer::class, 'order_id', 'order_id');
    }

    public function activityLog()
    {
        // Order by latest first
        return $this->hasMany(OrderActivityLog::class, 'order_id', 'order_id')->latest();
    }

    public function negotiations()
    {
        return $this->hasMany(OrderNegotiation::class, 'order_id', 'order_id')->orderBy('negotiation_round');
    }

    public function customerResponses()
    {
        return $this->hasMany(CustomerResponse::class, 'order_id', 'order_id');
    }
    """

    blade_view_code = """
    {{-- ========================================================== --}}
    {{--       ORDER ACTIVITY & NEGOTIATION HISTORY               --}}
    {{-- ========================================================== --}}
    <div class="row">
        <div class="col-md-12">
            <div class="info-card">
                <h5><i class="fas fa-history"></i> سجل نشاط الطلب</h5>
                <div class="timeline">
                    {{-- Loop through the activity log --}}
                    @forelse ($getRecord->activityLog as $activity)
                        <div>
                            {{-- Icon based on actor --}}
                            @php
                                $icon = 'fa-info-circle';
                                $color = 'bg-secondary';
                                if ($activity->actor_type == 'admin') { $icon = 'fa-user-shield'; $color = 'bg-primary'; }
                                elseif ($activity->actor_type == 'vendor') { $icon = 'fa-user-tie'; $color = 'bg-info'; }
                                elseif ($activity->actor_type == 'customer') { $icon = 'fa-user'; $color = 'bg-success'; }
                            @endphp
                            <i class="fas {{ $icon }} {{ $color }}"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ \\Carbon\\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                                <h3 class="timeline-header">
                                    <strong>{{ ucfirst($activity->actor_type) }}</strong>: {{ $activity->activity_description }}
                                </h3>

                                {{-- Optional: Add more details from the metadata if available --}}
                                @if($activity->metadata)
                                    <div class="timeline-body">
                                        <pre style="white-space: pre-wrap; word-wrap: break-word;">{{ json_encode(json_decode($activity->metadata), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div>
                            <i class="fas fa-info-circle bg-gray"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">لا يوجد سجل نشاط حتى الآن.</h3>
                            </div>
                        </div>
                    @endforelse
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    """

    print(f"\n{colors.HEADER}--- Step 3: Manual Updates & Next Steps ---{colors.ENDC}")
    print(f"{colors.BOLD}Action Required:{colors.ENDC} The script has created the necessary files. Now, follow these manual steps:")

    print(f"\n{colors.OKBLUE}1. Run the Migrations:{colors.ENDC}")
    print("   Open your terminal in the Laravel project root and run:")
    print(f"   {colors.WARNING}php artisan migrate{colors.ENDC}")

    print(f"\n{colors.OKBLUE}2. Update Your Order Model (app/Models/Order.php):{colors.ENDC}")
    print("   Add the following relationship methods to your existing Order model:")
    print(f"{colors.OKCYAN}{textwrap.dedent(order_model_update)}{colors.ENDC}")

    print(f"\n{colors.OKBLUE}3. Update Your Controller (e.g., app/Http/Controllers/Admin/OrderController.php):{colors.ENDC}")
    print("   Update the controller method that loads the order details view to eager load the new data:")
    print(f"{colors.OKCYAN}{textwrap.dedent(controller_logic)}{colors.ENDC}")

    print(f"\n{colors.OKBLUE}4. Update Your Blade View (e.g., resources/views/admin/order/details.blade.php):{colors.ENDC}")
    print("   Add this new section to your Blade file to display the activity timeline. A good place is after the 'Location Details' card.")
    print(f"{colors.OKCYAN}{textwrap.dedent(blade_view_code)}{colors.ENDC}")

    print(f"\n{colors.OKGREEN}{colors.BOLD}Integration Complete!{colors.ENDC}")
    print("After these manual steps, your application will be fully integrated with the new tracking system.")


def main():
    """Main function to run the script."""
    print(f"{colors.BOLD}{colors.HEADER}Starting Laravel Order Tracker Integration Script...{colors.ENDC}")

    # Check if running in a Laravel project directory
    if not os.path.exists('artisan'):
        print(f"{colors.FAIL}Error: 'artisan' file not found. Please run this script from the root of your Laravel project.{colors.ENDC}")
        return

    generate_migrations()
    generate_models()
    display_next_steps()

if __name__ == "__main__":
    main()
