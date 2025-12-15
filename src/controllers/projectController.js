const { Project, User, Task, TestCase } = require('../models');

const createProject = async (req, res) => {
  try {
    const { name, description, status, startDate, endDate, managerId } = req.body;

    const project = await Project.create({
      name,
      description,
      status,
      startDate,
      endDate,
      managerId: managerId || req.user.id
    });

    res.status(201).json({
      message: 'Project created successfully',
      project
    });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const getAllProjects = async (req, res) => {
  try {
    const projects = await Project.findAll({
      include: [
        { model: User, as: 'manager', attributes: ['id', 'username', 'email'] }
      ]
    });

    res.json({ projects });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const getProjectById = async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id, {
      include: [
        { model: User, as: 'manager', attributes: ['id', 'username', 'email'] },
        { 
          model: Task, 
          as: 'tasks',
          include: [{ model: User, as: 'assignee', attributes: ['id', 'username'] }]
        },
        { 
          model: TestCase, 
          as: 'testCases',
          include: [{ model: User, as: 'creator', attributes: ['id', 'username'] }]
        }
      ]
    });

    if (!project) {
      return res.status(404).json({ error: 'Project not found' });
    }

    res.json({ project });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const updateProject = async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id);

    if (!project) {
      return res.status(404).json({ error: 'Project not found' });
    }

    await project.update(req.body);

    res.json({
      message: 'Project updated successfully',
      project
    });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const deleteProject = async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id);

    if (!project) {
      return res.status(404).json({ error: 'Project not found' });
    }

    await project.destroy();

    res.json({ message: 'Project deleted successfully' });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

module.exports = {
  createProject,
  getAllProjects,
  getProjectById,
  updateProject,
  deleteProject
};
