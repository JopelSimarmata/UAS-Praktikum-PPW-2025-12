const { Task, User, Project } = require('../models');

const createTask = async (req, res) => {
  try {
    const { title, description, status, priority, projectId, assignedTo, dueDate } = req.body;

    const task = await Task.create({
      title,
      description,
      status,
      priority,
      projectId,
      assignedTo,
      dueDate
    });

    res.status(201).json({
      message: 'Task created successfully',
      task
    });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const getAllTasks = async (req, res) => {
  try {
    const { projectId } = req.query;
    const where = projectId ? { projectId } : {};

    const tasks = await Task.findAll({
      where,
      include: [
        { model: User, as: 'assignee', attributes: ['id', 'username', 'email'] },
        { model: Project, as: 'project', attributes: ['id', 'name'] }
      ]
    });

    res.json({ tasks });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const getTaskById = async (req, res) => {
  try {
    const task = await Task.findByPk(req.params.id, {
      include: [
        { model: User, as: 'assignee', attributes: ['id', 'username', 'email'] },
        { model: Project, as: 'project', attributes: ['id', 'name'] }
      ]
    });

    if (!task) {
      return res.status(404).json({ error: 'Task not found' });
    }

    res.json({ task });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const updateTask = async (req, res) => {
  try {
    const task = await Task.findByPk(req.params.id);

    if (!task) {
      return res.status(404).json({ error: 'Task not found' });
    }

    await task.update(req.body);

    res.json({
      message: 'Task updated successfully',
      task
    });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

const deleteTask = async (req, res) => {
  try {
    const task = await Task.findByPk(req.params.id);

    if (!task) {
      return res.status(404).json({ error: 'Task not found' });
    }

    await task.destroy();

    res.json({ message: 'Task deleted successfully' });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
};

module.exports = {
  createTask,
  getAllTasks,
  getTaskById,
  updateTask,
  deleteTask
};
