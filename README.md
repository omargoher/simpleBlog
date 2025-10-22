# Simple Blog

## End Points
### Auth Routes
- POST   /register
- POST   /login
- POST   /logout (Auth Required)

### Post Routes
- POST   /posts                   → create new post (Auth Required) 
- GET    /posts?search=keyword&page=1    → search posts 
- GET    /posts/{id}              → show one post
- PUT    /posts/{id}              → update post (Auth Required)
- DELETE /posts/{id}              → delete post (Auth Required)



### Comment Routes
- POST   /posts/{id}/comments     → create a new comment (Auth Required)
- GET    /posts/{id}/comments     → show comments for a specific post
- PUT    /comments/{id}           → update comment (Auth Required)
- DELETE /comments/{id}           → delete comment (Auth Required)


