require('dotenv').config();
const express = require('express');
const cors = require('cors');
const sequelize = require('./config/database');
const errorHandler = require('./middleware/errorHandler');

// Import routes
const authRoutes = require('./routes/authRoutes');
const projectRoutes = require('./routes/projectRoutes');
const taskRoutes = require('./routes/taskRoutes');
const testCaseRoutes = require('./routes/testCaseRoutes');

// Import models to ensure relationships are established
require('./models');

const app = express();

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Routes
app.get('/', (req, res) => {
  res.json({
    message: 'API Sistem Manajemen Proyek dan Testing',
    version: '1.0.0',
    endpoints: {
      auth: '/api/auth',
      projects: '/api/projects',
      tasks: '/api/tasks',
      testCases: '/api/test-cases'
    }
  });
});

app.use('/api/auth', authRoutes);
app.use('/api/projects', projectRoutes);
app.use('/api/tasks', taskRoutes);
app.use('/api/test-cases', testCaseRoutes);

// Error handler
app.use(errorHandler);

// Database sync and server start
const PORT = process.env.PORT || 3000;

const startServer = async () => {
  try {
    await sequelize.sync({ alter: true });
    console.log('Database synced successfully');

    app.listen(PORT, () => {
      console.log(`Server is running on port ${PORT}`);
      console.log(`API Documentation: http://localhost:${PORT}/`);
    });
  } catch (error) {
    console.error('Unable to start server:', error);
    process.exit(1);
  }
};

startServer();

module.exports = app;
