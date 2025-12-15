const { TestCase, User, Project } = require('../models');

const createTestCase = async (req, res) => {
  try {
    const { title, description, steps, expectedResult, status, priority, projectId } = req.body;

    const testCase = await TestCase.create({
      title,
      description,
      steps,
      expectedResult,
      status,
      priority,
      projectId,
      createdBy: req.user.id
    });

    res.status(201).json({
      message: 'Test case created successfully',
      testCase
    });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const getAllTestCases = async (req, res) => {
  try {
    const { projectId } = req.query;
    const where = projectId ? { projectId } : {};

    const testCases = await TestCase.findAll({
      where,
      include: [
        { model: User, as: 'creator', attributes: ['id', 'username', 'email'] },
        { model: User, as: 'lastTester', attributes: ['id', 'username', 'email'] },
        { model: Project, as: 'project', attributes: ['id', 'name'] }
      ]
    });

    res.json({ testCases });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const getTestCaseById = async (req, res) => {
  try {
    const testCase = await TestCase.findByPk(req.params.id, {
      include: [
        { model: User, as: 'creator', attributes: ['id', 'username', 'email'] },
        { model: User, as: 'lastTester', attributes: ['id', 'username', 'email'] },
        { model: Project, as: 'project', attributes: ['id', 'name'] }
      ]
    });

    if (!testCase) {
      return res.status(404).json({ error: 'Test case not found' });
    }

    res.json({ testCase });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const updateTestCase = async (req, res) => {
  try {
    const testCase = await TestCase.findByPk(req.params.id);

    if (!testCase) {
      return res.status(404).json({ error: 'Test case not found' });
    }

    await testCase.update(req.body);

    res.json({
      message: 'Test case updated successfully',
      testCase
    });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const executeTestCase = async (req, res) => {
  try {
    const testCase = await TestCase.findByPk(req.params.id);

    if (!testCase) {
      return res.status(404).json({ error: 'Test case not found' });
    }

    const { status } = req.body;

    await testCase.update({
      status,
      lastTestedBy: req.user.id,
      lastTestedAt: new Date()
    });

    res.json({
      message: 'Test case executed successfully',
      testCase
    });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const deleteTestCase = async (req, res) => {
  try {
    const testCase = await TestCase.findByPk(req.params.id);

    if (!testCase) {
      return res.status(404).json({ error: 'Test case not found' });
    }

    await testCase.destroy();

    res.json({ message: 'Test case deleted successfully' });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

module.exports = {
  createTestCase,
  getAllTestCases,
  getTestCaseById,
  updateTestCase,
  executeTestCase,
  deleteTestCase
};
