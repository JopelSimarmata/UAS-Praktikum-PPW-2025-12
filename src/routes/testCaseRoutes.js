const express = require('express');
const router = express.Router();
const {
  createTestCase,
  getAllTestCases,
  getTestCaseById,
  updateTestCase,
  executeTestCase,
  deleteTestCase
} = require('../controllers/testCaseController');
const { auth } = require('../middleware/auth');

router.post('/', auth, createTestCase);
router.get('/', auth, getAllTestCases);
router.get('/:id', auth, getTestCaseById);
router.put('/:id', auth, updateTestCase);
router.post('/:id/execute', auth, executeTestCase);
router.delete('/:id', auth, deleteTestCase);

module.exports = router;
