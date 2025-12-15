const express = require('express');
const router = express.Router();
const {
  createProject,
  getAllProjects,
  getProjectById,
  updateProject,
  deleteProject
} = require('../controllers/projectController');
const { auth, authorize } = require('../middleware/auth');

router.post('/', auth, authorize('admin', 'manager'), createProject);
router.get('/', auth, getAllProjects);
router.get('/:id', auth, getProjectById);
router.put('/:id', auth, authorize('admin', 'manager'), updateProject);
router.delete('/:id', auth, authorize('admin', 'manager'), deleteProject);

module.exports = router;
