const User = require('./User');
const Project = require('./Project');
const Task = require('./Task');
const TestCase = require('./TestCase');

// Define relationships
User.hasMany(Project, { foreignKey: 'managerId', as: 'managedProjects' });
Project.belongsTo(User, { foreignKey: 'managerId', as: 'manager' });

Project.hasMany(Task, { foreignKey: 'projectId', as: 'tasks' });
Task.belongsTo(Project, { foreignKey: 'projectId', as: 'project' });

User.hasMany(Task, { foreignKey: 'assignedTo', as: 'assignedTasks' });
Task.belongsTo(User, { foreignKey: 'assignedTo', as: 'assignee' });

Project.hasMany(TestCase, { foreignKey: 'projectId', as: 'testCases' });
TestCase.belongsTo(Project, { foreignKey: 'projectId', as: 'project' });

User.hasMany(TestCase, { foreignKey: 'createdBy', as: 'createdTestCases' });
TestCase.belongsTo(User, { foreignKey: 'createdBy', as: 'creator' });

User.hasMany(TestCase, { foreignKey: 'lastTestedBy', as: 'testedTestCases' });
TestCase.belongsTo(User, { foreignKey: 'lastTestedBy', as: 'lastTester' });

module.exports = {
  User,
  Project,
  Task,
  TestCase
};
