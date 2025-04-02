document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('particle-canvas');
    const ctx = canvas.getContext('2d');
    const container = document.querySelector('.container-fluid');
    
    // Set canvas size to match container
    function setCanvasSize() {
      const rect = container.getBoundingClientRect();
      canvas.width = rect.width;
      canvas.height = rect.height;
    }
    
    // Initialize canvas size
    setCanvasSize();
    
    // Update canvas size on window resize
    window.addEventListener('resize', setCanvasSize);
    
    // Mouse position
    let mouseX = 0;
    let mouseY = 0;
    let isMouseInContainer = false;
    
    // Track mouse position
    container.addEventListener('mousemove', function(e) {
      const rect = container.getBoundingClientRect();
      mouseX = e.clientX - rect.left;
      mouseY = e.clientY - rect.top;
      isMouseInContainer = true;
    });
    
    container.addEventListener('mouseleave', function() {
      isMouseInContainer = false;
    });
    
    // Particle settings
    const particleCount = 80;
    const particleRadius = 2;
    const particleColor = 'rgba(255, 255, 255, 0.8)';
    const lineColor = 'rgba(255, 255, 255, 0.2)';
    const lineWidth = 1;
    const connectionDistance = 120;
    const mouseInfluenceRadius = 150;
    const mouseRepelStrength = 0.1;
    
    // Particle class
    class Particle {
      constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.vx = (Math.random() - 0.5) * 0.5;
        this.vy = (Math.random() - 0.5) * 0.5;
        this.radius = particleRadius;
      }
      
      update() {
        // Move particles
        this.x += this.vx;
        this.y += this.vy;
        
        // Mouse influence
        if (isMouseInContainer) {
          const dx = mouseX - this.x;
          const dy = mouseY - this.y;
          const distance = Math.sqrt(dx * dx + dy * dy);
          
          if (distance < mouseInfluenceRadius) {
            // Repel from mouse
            const angle = Math.atan2(dy, dx);
            const force = (mouseInfluenceRadius - distance) / mouseInfluenceRadius;
            
            this.vx -= Math.cos(angle) * force * mouseRepelStrength;
            this.vy -= Math.sin(angle) * force * mouseRepelStrength;
          }
        }
        
        // Boundary check - bounce off edges
        if (this.x < 0 || this.x > canvas.width) {
          this.vx = -this.vx;
        }
        
        if (this.y < 0 || this.y > canvas.height) {
          this.vy = -this.vy;
        }
        
        // Constrain positions
        this.x = Math.max(0, Math.min(canvas.width, this.x));
        this.y = Math.max(0, Math.min(canvas.height, this.y));
        
        // Add some random movement
        this.vx += (Math.random() - 0.5) * 0.01;
        this.vy += (Math.random() - 0.5) * 0.01;
        
        // Limit velocity
        const speed = Math.sqrt(this.vx * this.vx + this.vy * this.vy);
        if (speed > 1.5) {
          this.vx = (this.vx / speed) * 1.5;
          this.vy = (this.vy / speed) * 1.5;
        }
      }
      
      draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = particleColor;
        ctx.fill();
      }
    }
    
    // Create particles
    const particles = [];
    for (let i = 0; i < particleCount; i++) {
      particles.push(new Particle());
    }
    
    // Draw connections between particles
    function drawConnections() {
      ctx.strokeStyle = lineColor;
      ctx.lineWidth = lineWidth;
      
      for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
          const dx = particles[i].x - particles[j].x;
          const dy = particles[i].y - particles[j].y;
          const distance = Math.sqrt(dx * dx + dy * dy);
          
          if (distance < connectionDistance) {
            // Adjust opacity based on distance
            const opacity = 1 - (distance / connectionDistance);
            ctx.strokeStyle = `rgba(255, 255, 255, ${opacity * 0.2})`;
            
            ctx.beginPath();
            ctx.moveTo(particles[i].x, particles[i].y);
            ctx.lineTo(particles[j].x, particles[j].y);
            ctx.stroke();
          }
        }
      }
    }
    
    // Animation loop
    function animate() {
      // Clear canvas
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      
      // Update and draw particles
      for (let i = 0; i < particles.length; i++) {
        particles[i].update();
        particles[i].draw();
      }
      
      // Draw connections
      drawConnections();
      
      // Request next frame
      requestAnimationFrame(animate);
    }
    
    // Start animation
    animate();
  });