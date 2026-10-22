## Overview

### Project Title

DPHP: Digital portfolio hosting platform

### One-Sentence Summary

This app allows students to quickly create a professional digital portfolio without having to deal with the headache of setting up a full tech stack and host a server.

### Problem And Purpose

Most available website hosting services have very limited or even nonexistant templates, which can be hostile to students who aren't accustomed to creating a website. DPHP serves to solve that issue, providing easy to use templates to students who may not be familiar with the process of creating a website.

### Target Users

High Tech High North County students interested in creating a digital portfolio via a more streamlined process than other, proprietary website services.

## Project Scope And Constraints

### Core Features

+ A quick, easy to use template-based portfolio creation process
+ Text editing on each website
+ Adding custom links to each portfolio that link to whatever a person decides
+ Each portfolio is only editable by the user that created it and admins

### Optional Features

+ Image uploads (Highly reccomended)
+ More templates, each with a different theme (e.g. computer science, humanities, stem, different categories of common fields a student may want to go into)
+ Admin accounts 

### Data You Need To Store

+ User logins (passwords, emails)
+ List of portfolios

### Success Criteria (What “Done” Means)

+ User able to create account, log in, log out
+ User able to create portfolio using a template
+ User able to edit the portfolio
+ Edits to a portfolio are saved permanently

### Constraints And Risks

+ Complex creation logic
  + Focus on the logic before making templates
+ Lots of templates
  + Only make one for MVP
+ Accounts and admin roles
  + Only user accounts for MVP

## Tech Stack (What We Are Using)

### Frontend

+ HTML creates the structure of each and every template.
+ CSS provides the theme and visual appearance of each template.
+ Javascript helps each page feel alive, with buttons that respond to input, etc.

### Backend

+ Apache handles running the server
+ PHP connects the frontend to the backend
+ MySQL manages the database

### Database

+ The database stores the user's password and email along with a unique ID
+ A seperate table stores the names of portfolios, the associated user and a unique ID

### Tools (Development)

+ Servbay to run the website locally
+ Git and Github to manage code

## Technical Plan And Tools

### How The Pieces Connect

+ User creates an account
+ Password, username and unique ID are saved to the database
+ Newly logged in user creates a portfolio
+ Name, unique ID and timestamp of portfolio are saved to the database

### Database Notes

+ database name: `portfolio_hosting`
+ tables: `users`, `portfolios`
+ `users` columns: `id`, `email`, `password`
+ `portfolios` columns: `id`, `name`, `timestamp`
+ 
### “New Tech” Rule

+ Creating and editing files on the fly with PHP, javascript, and HTML.

## Approval Check: MVP (Minimum Viable Product)

### MVP Sentence

+ A user can create an account, create a portfolio, and edit the portfolio while logged in, and the app saves users and a list of portfolios in the database.

### MVP Checklist

+ Account creation
+ 2 tables for users and portfolios
+ Search function for portfolios referencing the portfolio table

### What’s Not In The MVP

+ Image uploads
+ Administration accounts (management will have to be done by manually editing/deleting the offending file/files)
+ Account management (password, email, username changes)

### Confidence Check

+ Due to only having one template and no image uploading, I am confident the MVP will be possible.
+ The greatest risk comes from the logic behind creating, editing and saving the page, so I will focus on the logic before creating any templates.

### Stretch Goals

+ Image uploads
+ More templates

### Contributors

+ Main programmer
  - Sam H
+ Consultant
  - Franklin V
