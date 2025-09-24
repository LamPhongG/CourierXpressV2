@extends('layouts.unified')

@section('title', 'Admin - Shippers Management | CourierXpress')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
@endsection

@section('navigation')
    <a href="/admin/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/admin/orders" class="text-gray-700 hover:text-red-600">Orders</a>
    <a href="/admin/users" class="text-gray-700 hover:text-red-600">Users</a>
    <a href="/admin/agents" class="text-gray-700 hover:text-red-600">Agents</a>
    <a href="/admin/shippers" class="text-red-600 font-medium">Shipper</a>
    <a href="/admin/reports" class="text-gray-700 hover:text-red-600">Reports</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Shippers Management</h2>
                <p class="mt-2 text-gray-600">Manage shippers list and delivery assignments</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="openAddShipperModal()" class="bg-primary hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i> Add Shipper
                </button>
                <button onclick="exportShippers()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i> Export Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-motorcycle text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Total shippers</p>
                    <p id="totalShippers" class="text-2xl font-bold text-blue-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Active</p>
                    <p id="activeShippers" class="text-2xl font-bold text-green-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-truck text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Delivering</p>
                    <p id="busyShippers" class="text-2xl font-bold text-yellow-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-pause-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Inactive</p>
                    <p id="inactiveShippers" class="text-2xl font-bold text-red-600">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <table id="shippersTable" class="w-full">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Shipper info</th>
                        <th>Area</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Deliveries</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Shipper Modal -->
    <div id="shipperModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add new shipper</h3>
                    <button onclick="hideShipperModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="shipperForm" onsubmit="handleShipperSubmit(event)">
                    <div class="space-y-4">
                        <input type="hidden" id="shipperId">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full name</label>
                            <input type="text" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone number</label>
                            <input type="tel" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Operating area</label>
                            <select id="area" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="north">North</option>
                                <option value="central">Central</option>
                                <option value="south">South</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle type</label>
                            <select id="vehicleType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="motorcycle">Motorcycle</option>
                                <option value="car">Car</option>
                                <option value="truck">Truck</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">License plate</label>
                            <input type="text" id="vehicleNumber" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-primary hover:bg-red-700 text-white py-2 px-4 rounded-md transition-colors">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Shipper Details Modal -->
    <div id="shipperDetailsModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="detailsTitle">
                                Shipper details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Basic Information -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Basic information</h4>
                                    <dl class="space-y-2">
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Full name:</dt>
                                            <dd class="text-sm text-gray-900" id="detailsName"></dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Phone:</dt>
                                            <dd class="text-sm text-gray-900" id="detailsPhone"></dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Email:</dt>
                                            <dd class="text-sm text-gray-900" id="detailsEmail"></dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Area:</dt>
                                            <dd class="text-sm text-gray-900" id="detailsArea"></dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Status:</dt>
                                            <dd id="detailsStatus"></dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Statistics -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Statistics</h4>
                                    <dl class="space-y-2">
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Total orders:</dt>
                                            <dd class="text-sm text-gray-900" id="detailsTotalOrders"></dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Success rate:</dt>
                                            <dd class="text-sm text-gray-900" id="detailsSuccessRate"></dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Average rating:</dt>
                                            <dd class="text-sm text-gray-900" id="detailsRating"></dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Average delivery time:</dt>
                                            <dd class="text-sm text-gray-900" id="detailsAvgTime"></dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Recent Orders -->
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Recent orders</h4>
                                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                        <ul role="list" class="divide-y divide-gray-200" id="detailsRecentOrders">
                                            <!-- Recent orders will be dynamically added here -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeDetailsModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let shippers = [];
    let table;

    $(document).ready(function() {
        // Initialize DataTable
        table = $('#shippersTable').DataTable({
            columns: [
                { data: 'id' },
                { 
                    data: null,
                    render: function(data) {
                        return `
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="${data.avatar || '/images/default-avatar.png'}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${data.name}</div>
                                    <div class="text-sm text-gray-500">${data.email}</div>
                                </div>
                            </div>
                        `;
                    }
                },
                { data: 'area' },
                {
                    data: 'rating',
                    render: function(data) {
                        return getRatingStars(data);
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        const statusClasses = {
                            active: 'bg-green-100 text-green-800',
                            inactive: 'bg-red-100 text-red-800'
                        };
                        const statusText = {
                            active: 'Active',
                            inactive: 'Inactive'
                        };
                        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClasses[data]}">${statusText[data]}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div>
                                <div class="text-sm text-gray-900">${data.total_orders || 0} orders</div>
                                <div class="text-sm text-gray-500">${data.success_rate || 0}% success</div>
                            </div>
                        `;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="flex space-x-2">
                                <button onclick="viewShipperDetails('${data.id}')" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editShipper('${data.id}')" class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="toggleShipperStatus('${data.id}')" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button onclick="deleteShipper('${data.id}')" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        // Load initial data
        loadShippers();
    });

    // Load shippers data
    async function loadShippers() {
        try {
            const response = await fetch('/dev/test-shippers-api');
            shippers = await response.json();
            
            // Update table
            table.clear();
            table.rows.add(shippers);
            table.draw();

            // Update statistics
            updateStats();
        } catch (error) {
            console.error('Error loading shippers:', error);
            alert('Failed to load data. Please try again.');
        }
    }

    // Update statistics
    function updateStats() {
        const stats = shippers.reduce((acc, shipper) => {
            acc[shipper.status]++;
            return acc;
        }, { active: 0, inactive: 0, busy: 0 });

        document.getElementById('totalShippers').textContent = shippers.length;
        document.getElementById('activeShippers').textContent = stats.active;
        document.getElementById('busyShippers').textContent = stats.busy || 0;
        document.getElementById('inactiveShippers').textContent = stats.inactive;
    }

    // Show add shipper modal
    function openAddShipperModal() {
        document.getElementById('modalTitle').textContent = 'Add new shipper';
        document.getElementById('shipperId').value = '';
        document.getElementById('shipperForm').reset();
        document.getElementById('shipperModal').classList.remove('hidden');
        document.getElementById('shipperModal').classList.add('flex');
    }

    // Hide shipper modal
    function hideShipperModal() {
        document.getElementById('shipperModal').classList.remove('flex');
        document.getElementById('shipperModal').classList.add('hidden');
    }

    // Edit shipper
    function editShipper(id) {
        const shipper = shippers.find(s => s.id === id);
        if (!shipper) return;

        document.getElementById('modalTitle').textContent = 'Edit shipper';
        document.getElementById('shipperId').value = shipper.id;
        document.getElementById('name').value = shipper.name;
        document.getElementById('phone').value = shipper.phone;
        document.getElementById('email').value = shipper.email;
        document.getElementById('area').value = shipper.area;
        document.getElementById('vehicleType').value = shipper.vehicle_type;
        document.getElementById('vehicleNumber').value = shipper.vehicle_number;
        document.getElementById('status').value = shipper.status;

        document.getElementById('shipperModal').classList.remove('hidden');
        document.getElementById('shipperModal').classList.add('flex');
    }

    // Handle form submission
    async function handleShipperSubmit(event) {
        event.preventDefault();

        const formData = {
            id: document.getElementById('shipperId').value,
            name: document.getElementById('name').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            area: document.getElementById('area').value,
            vehicle_type: document.getElementById('vehicleType').value,
            vehicle_number: document.getElementById('vehicleNumber').value,
            status: document.getElementById('status').value
        };

        try {
            const url = formData.id ? `/api/admin/shippers/${formData.id}` : '/api/admin/shippers';
            const method = formData.id ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) throw new Error('Network response was not ok');

            hideShipperModal();
            loadShippers();
            alert(formData.id ? 'Updated successfully!' : 'Created successfully!');
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    }

    // Toggle shipper status
    async function toggleShipperStatus(id) {
        if (!confirm('Are you sure you want to change this shipper\'s status?')) return;

        try {
            const response = await fetch(`/api/admin/shippers/${id}/toggle-status`, {
                method: 'PUT'
            });

            if (!response.ok) throw new Error('Network response was not ok');

            loadShippers();
            alert('Status updated successfully!');
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    }

    // Delete shipper
    async function deleteShipper(id) {
        if (!confirm('Are you sure you want to delete this shipper?')) return;

        try {
            const response = await fetch(`/api/admin/shippers/${id}`, {
                method: 'DELETE'
            });

            if (!response.ok) throw new Error('Network response was not ok');

            loadShippers();
            alert('Shipper deleted successfully!');
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    }

    // View shipper details
    function viewShipperDetails(id) {
        // Add view details functionality here
        alert('This feature is under development');
    }

    // Export to Excel
    function exportShippers() {
        // Add export functionality here
        alert('This feature is under development');
    }

    // Helper function for rating stars
    function getRatingStars(rating) {
        if (!rating) return 'No ratings yet';
        
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - Math.ceil(rating);
        
        return Array(fullStars).fill('<i class="fas fa-star text-yellow-400"></i>')
            .concat(hasHalfStar ? ['<i class="fas fa-star-half-alt text-yellow-400"></i>'] : [])
            .concat(Array(emptyStars).fill('<i class="far fa-star text-yellow-400"></i>'))
            .join('');
    }

    // Check authentication on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkAuth('admin');
    });
</script>
@endsection
