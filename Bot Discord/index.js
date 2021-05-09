const Discord = require('discord.js')

/**
exports.MySQL = require("mysql");
exports.Connection = exports.MySQL.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : '',
    database : 'stonkser'
});

exports.Connection.connect(function(err) {
    if (err) { return console.error('Error in the connection: ' + err.stack); }
    console.log(exports.Colors.rainbow('Connecté à la base de données avec l\'identifiant: ' + exports.Connection.threadId));
});
**/

exports.Version = "v1.0"

exports.Client = new Discord.Client()
exports.Client.commands = new Discord.Collection();
exports.Config = require('./datafolder/config.json');
exports.Colors = require('colors');

const CommandRegistry = require('./commands/CommandRegister')
CommandRegistry.registerCommand(exports.Client.commands, "link", true, "commands/minecraft/", "LinkCommand", exports.Colors)

require('./listener/members/Messages')

console.log(exports.Colors.rainbow("Museum Bot Oppérationel"))
exports.Client.login(exports.Config.token)