<!DOCTYPE html>
<html>
<head>
    <title>Agent Login Test - CourierXpress</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { background: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Agent Login Test - CourierXpress</h1>
        <p>Test the Agent authentication and dashboard access</p>
        
        <div>
            <button class="btn" onclick="testAgentLogin()">
                🔐 Quick Login as Agent
            </button>
            
            <button class="btn" onclick="testCurrentAuth()">
                👤 Check Current Auth Status
            </button>
            
            <button class="btn btn-success" onclick="goToAgentDashboard()">
                📊 Go to Agent Dashboard
            </button>
            
            <button class="btn" onclick="createTestShippers()">
                🚚 Create Test Shippers
            </button>
        </div>
        
        <div id="result"></div>
    </div>
    
    <script>
        async function testAgentLogin() {
            showResult('🔄 Logging in as Agent...', 'info');
            
            try {
                const response = await fetch('/api/dev/quick-login-agent');
                const data = await response.json();
                
                if (data.success) {
                    showResult(`✅ Login Success!\n\nUser Info:\n- Name: ${data.user.name}\n- Email: ${data.user.email}\n- Role: ${data.user.role}\n- City: ${data.user.city}\n\nRedirect: ${data.redirect_to}`, 'success');
                    
                    // Auto redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect_to;
                    }, 2000);
                } else {
                    showResult(`❌ Login Failed: ${data.message}`, 'error');
                }
            } catch (error) {
                showResult(`💥 Error: ${error.message}`, 'error');
            }
        }
        
        async function testCurrentAuth() {
            showResult('🔍 Checking authentication status...', 'info');
            
            try {
                const response = await fetch('/api/dev/debug-current-user');
                const data = await response.json();
                
                if (data.authenticated) {
                    showResult(`✅ Authenticated!\n\nUser Info:\n- Name: ${data.user.name}\n- Email: ${data.user.email}\n- Role: ${data.user.role}\n- Status: ${data.user.status}`, 'success');
                } else {
                    showResult(`❌ Not authenticated: ${data.message}`, 'error');
                }
            } catch (error) {
                showResult(`💥 Error: ${error.message}`, 'error');
            }
        }
        
        function goToAgentDashboard() {
            window.location.href = '/agent/dashboard';
        }
        
        async function createTestShippers() {
            showResult('🚚 Tạo shipper test...', 'info');
            
            try {
                const response = await fetch('/api/dev/create-test-shippers');
                const data = await response.json();
                
                if (data.success) {
                    showResult(`✅ Tạo shipper thành công!\n\nShipper đã tạo:\n${data.created_shippers.join('\n')}\n\nTổng số shipper: ${data.total_shippers}`, 'success');
                } else {
                    showResult(`❌ Lỗi: ${data.message}`, 'error');
                }
            } catch (error) {
                showResult(`💥 Lỗi: ${error.message}`, 'error');
            }
        }
        
        function showResult(message, type) {
            const resultDiv = document.getElementById('result');
            const className = type === 'success' ? 'result success' : 
                             type === 'error' ? 'result error' : 'result';
            
            resultDiv.innerHTML = `<div class="${className}"><pre>${message}</pre></div>`;
        }
        
        // Auto-check auth status on load
        document.addEventListener('DOMContentLoaded', testCurrentAuth);
    </script>
</body>
</html>