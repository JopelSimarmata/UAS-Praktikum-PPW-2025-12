<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Sistem Manajemen Proyek</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 1200px;
            width: 100%;
            padding: 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .header h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 1.1em;
        }
        
        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 15px;
        }
        
        .section {
            margin: 30px 0;
        }
        
        .section h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
        }
        
        .endpoint {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        
        .method {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            margin-right: 10px;
            font-size: 0.85em;
        }
        
        .method.get { background: #10b981; color: white; }
        .method.post { background: #3b82f6; color: white; }
        .method.put { background: #f59e0b; color: white; }
        .method.delete { background: #ef4444; color: white; }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        .stat-item {
            text-align: center;
            color: white;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            display: block;
        }
        
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            margin: 10px 10px 10px 0;
        }
        
        .btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8em;
            }
            
            .stats {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 API Sistem Manajemen Proyek</h1>
            <p class="subtitle">RESTful API dengan Laravel - Complete with Testing</p>
            <span class="status-badge">✅ Production Ready</span>
        </div>
        
        <div class="stats">
            <div class="stat-item">
                <span class="stat-number">40</span>
                <span class="stat-label">Test Cases</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">100%</span>
                <span class="stat-label">Pass Rate</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">14</span>
                <span class="stat-label">API Endpoints</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">164</span>
                <span class="stat-label">Assertions</span>
            </div>
        </div>
        
        <div class="section">
            <h2>✨ Fitur Utama</h2>
            <div class="grid">
                <div class="card">
                    <h3>🔐 Authentication</h3>
                    <p>Token-based authentication dengan Laravel Sanctum untuk secure API access</p>
                </div>
                <div class="card">
                    <h3>📋 Project Management</h3>
                    <p>CRUD operations lengkap untuk mengelola projects dengan validasi</p>
                </div>
                <div class="card">
                    <h3>✅ Task Management</h3>
                    <p>Kelola tasks dalam project dengan status dan priority tracking</p>
                </div>
                <div class="card">
                    <h3>🧪 Comprehensive Testing</h3>
                    <p>40 test cases dengan positive dan negative scenarios</p>
                </div>
            </div>
        </div>
        
        <div class="section">
            <h2>📡 API Endpoints</h2>
            
            <h3 style="color: #666; margin: 20px 0 10px 0;">Authentication</h3>
            <div class="endpoint">
                <span class="method post">POST</span> /api/register - Register new user
            </div>
            <div class="endpoint">
                <span class="method post">POST</span> /api/login - Login user
            </div>
            <div class="endpoint">
                <span class="method get">GET</span> /api/me - Get user profile
            </div>
            <div class="endpoint">
                <span class="method post">POST</span> /api/logout - Logout user
            </div>
            
            <h3 style="color: #666; margin: 20px 0 10px 0;">Projects</h3>
            <div class="endpoint">
                <span class="method get">GET</span> /api/projects - Get all projects
            </div>
            <div class="endpoint">
                <span class="method post">POST</span> /api/projects - Create project
            </div>
            <div class="endpoint">
                <span class="method get">GET</span> /api/projects/{id} - Get specific project
            </div>
            <div class="endpoint">
                <span class="method put">PUT</span> /api/projects/{id} - Update project
            </div>
            <div class="endpoint">
                <span class="method delete">DELETE</span> /api/projects/{id} - Delete project
            </div>
            
            <h3 style="color: #666; margin: 20px 0 10px 0;">Tasks</h3>
            <div class="endpoint">
                <span class="method get">GET</span> /api/projects/{projectId}/tasks - Get all tasks
            </div>
            <div class="endpoint">
                <span class="method post">POST</span> /api/projects/{projectId}/tasks - Create task
            </div>
            <div class="endpoint">
                <span class="method get">GET</span> /api/projects/{projectId}/tasks/{taskId} - Get task
            </div>
            <div class="endpoint">
                <span class="method put">PUT</span> /api/projects/{projectId}/tasks/{taskId} - Update task
            </div>
            <div class="endpoint">
                <span class="method delete">DELETE</span> /api/projects/{projectId}/tasks/{taskId} - Delete task
            </div>
        </div>
        
        <div class="section">
            <h2>📚 Dokumentasi</h2>
            <a href="/api" class="btn">API Base URL</a>
            <p style="margin-top: 20px; color: #666;">
                <strong>Files tersedia:</strong><br>
                • <code>API_DOCUMENTATION.md</code> - Complete API documentation<br>
                • <code>LAPORAN_TESTING.md</code> - Comprehensive testing report<br>
                • <code>Project_Management_API.postman_collection.json</code> - Postman collection
            </p>
        </div>
        
        <div class="section">
            <h2>🎯 Quick Start</h2>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; font-family: monospace;">
                <p style="color: #666; margin-bottom: 10px;"># Run migrations</p>
                <p style="margin-bottom: 15px;">php artisan migrate</p>
                
                <p style="color: #666; margin-bottom: 10px;"># Run tests</p>
                <p style="margin-bottom: 15px;">php artisan test</p>
                
                <p style="color: #666; margin-bottom: 10px;"># Start server</p>
                <p>php artisan serve</p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Teknologi:</strong> Laravel 12.42.0 | PHP 8.1+ | SQLite/MySQL | Laravel Sanctum</p>
            <p style="margin-top: 10px;">Built with ❤️ for Project Management</p>
        </div>
    </div>
</body>
</html>
