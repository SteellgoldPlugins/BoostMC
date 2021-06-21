const Discord = require("discord.js");
const { fs } = require("../index");

module.exports.run = async (Client, message, args) => {
    if(!message.guild) return;
    const connection = await message.member.voice.channel.join();
}